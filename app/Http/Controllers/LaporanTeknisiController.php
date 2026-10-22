<?php

namespace App\Http\Controllers;

use App\Models\LaporanTeknisi;
use App\Models\PenugasanTeknisi;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanTeknisiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR LAPORAN TEKNISI
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $teknisi = Auth::user()->teknisi;

        if (!$teknisi) {
            abort(404, 'Data teknisi tidak ditemukan.');
        }

        $laporan = LaporanTeknisi::with([
            'penugasan.tiketGangguan.pelanggan.user',
            'penugasan.tiketGangguan.laporanGangguan',
            'penugasan.perbaikan',
        ])
        ->where('teknisi_id', $teknisi->id)
        ->latest('tanggal_laporan')
        ->get();

        /*
        |--------------------------------------------------------------------------
        | ID PENUGASAN YANG SUDAH MEMILIKI LAPORAN
        |--------------------------------------------------------------------------
        */

        $laporanIds = $laporan
            ->pluck('penugasan_teknisi_id')
            ->filter()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | TUGAS SELESAI YANG BELUM DIBUAT LAPORANNYA
        |--------------------------------------------------------------------------
        */

        $tugasBelumDilaporkan = PenugasanTeknisi::with([
            'tiketGangguan.pelanggan.user',
            'tiketGangguan.laporanGangguan',
            'perbaikan',
        ])
        ->where('teknisi_id', $teknisi->id)
        ->where('status', 'selesai')
        ->whereNotIn('id', $laporanIds)
        ->latest()
        ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalSelesai = PenugasanTeknisi::where(
            'teknisi_id',
            $teknisi->id
        )
        ->where('status', 'selesai')
        ->count();

        $belumDilaporkan =
            $tugasBelumDilaporkan->count();

        $laporanTerkirim =
            $laporan->count();

        $laporanDisetujui =
            $laporan
                ->where('status', 'disetujui')
                ->count();

        return view(
            'teknisi.laporan-teknisi',
            [
                'teknisi' =>
                    $teknisi,

                'laporan' =>
                    $laporan,

                'tugasBelumDilaporkan' =>
                    $tugasBelumDilaporkan,

                'totalSelesai' =>
                    $totalSelesai,

                'belumDilaporkan' =>
                    $belumDilaporkan,

                'laporanTerkirim' =>
                    $laporanTerkirim,

                'laporanDisetujui' =>
                    $laporanDisetujui,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM BUAT LAPORAN
    |--------------------------------------------------------------------------
    */

    public function create($id)
    {
        $teknisi = Auth::user()->teknisi;

        if (!$teknisi) {
            abort(404, 'Data teknisi tidak ditemukan.');
        }

        $penugasan = PenugasanTeknisi::with([
            'tiketGangguan.pelanggan.user',
            'tiketGangguan.laporanGangguan',
            'perbaikan',
            'teknisi',
        ])
        ->where('teknisi_id', $teknisi->id)
        ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CEK LAPORAN YANG SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $laporan = LaporanTeknisi::where(
            'penugasan_teknisi_id',
            $id
        )->first();

        return view(
            'teknisi.buat-laporan',
            compact(
                'penugasan',
                'laporan'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN / UPDATE LAPORAN TEKNISI
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $id
    ) {
        $teknisi = Auth::user()->teknisi;

        if (!$teknisi) {
            abort(404, 'Data teknisi tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'hasil_perbaikan' =>
                'required|string',

            'tindakan' =>
                'required|string',

            'catatan' =>
                'nullable|string',

            'bukti_foto' =>
                'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL PENUGASAN
        |--------------------------------------------------------------------------
        */

        $penugasan = PenugasanTeknisi::with([
            'tiketGangguan',
            'perbaikan',
        ])
        ->where(
            'teknisi_id',
            $teknisi->id
        )
        ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | WAKTU INDONESIA
        |--------------------------------------------------------------------------
        */

        $tanggal = now('Asia/Jakarta');

        /*
        |--------------------------------------------------------------------------
        | CEK LAPORAN LAMA
        |--------------------------------------------------------------------------
        */

        $laporan = LaporanTeknisi::where(
            'penugasan_teknisi_id',
            $id
        )->first();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE LAPORAN
        |--------------------------------------------------------------------------
        */

        if ($laporan) {

            $laporan->update([
                'hasil_perbaikan' =>
                    $request->hasil_perbaikan,

                'tindakan' =>
                    $request->tindakan,

                'catatan' =>
                    $request->catatan,

                'status' =>
                    'menunggu_approval',

                'tanggal_laporan' =>
                    $tanggal,
            ]);

        } else {

            $nomor =
                (LaporanTeknisi::max('id') ?? 0) + 1;

            $laporan =
                LaporanTeknisi::create([

                    'id_laporan' =>
                        'LPT-' .
                        str_pad(
                            $nomor,
                            4,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'penugasan_teknisi_id' =>
                        $id,

                    'teknisi_id' =>
                        $teknisi->id,

                    'pelanggan_id' =>
                        $penugasan
                            ->tiketGangguan
                            ->pelanggan_id,

                    'hasil_perbaikan' =>
                        $request->hasil_perbaikan,

                    'tindakan' =>
                        $request->tindakan,

                    'catatan' =>
                        $request->catatan,

                    'status' =>
                        'menunggu_approval',

                    'tanggal_laporan' =>
                        $tanggal,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS PENUGASAN MENJADI SELESAI
        |--------------------------------------------------------------------------
        */

        $penugasan->update([
            'status' =>
                'selesai',
        ]);

        /*
        |--------------------------------------------------------------------------
        | STATUS TIKET MENJADI SELESAI
        |--------------------------------------------------------------------------
        */

        if ($penugasan->tiketGangguan) {

            $penugasan->tiketGangguan->update([
                'status' =>
                    'selesai',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS PERBAIKAN MENJADI SELESAI
        |--------------------------------------------------------------------------
        */

        if ($penugasan->perbaikan) {

            $penugasan->perbaikan->update([
                'status' =>
                    'selesai',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | FOTO BUKTI PERBAIKAN
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('bukti_foto')) {

            $perbaikan =
                $penugasan->perbaikan;

            if ($perbaikan) {

                $file =
                    $request->file('bukti_foto');

                /*
                |--------------------------------------------------------------------------
                | FOLDER TEKNISI
                |--------------------------------------------------------------------------
                */

                $folderTeknisi =
                    'teknisi/' .
                    $teknisi->id_teknisi .
                    '/bukti-perbaikan';

                /*
                |--------------------------------------------------------------------------
                | HAPUS FOTO LAMA
                |--------------------------------------------------------------------------
                */

                if (
                    $perbaikan->bukti_foto &&
                    Storage::disk('public')->exists(
                        $perbaikan->bukti_foto
                    )
                ) {
                    Storage::disk('public')->delete(
                        $perbaikan->bukti_foto
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | NAMA FILE BERDASARKAN TANGGAL + WAKTU
                |--------------------------------------------------------------------------
                */

                $namaFile =
                    now('Asia/Jakarta')
                    ->format('Ymd-His') .
                    '.' .
                    $file->getClientOriginalExtension();

                /*
                |--------------------------------------------------------------------------
                | SIMPAN FOTO
                |--------------------------------------------------------------------------
                */

                $foto =
                    $file->storeAs(
                        $folderTeknisi,
                        $namaFile,
                        'public'
                    );

                /*
                |--------------------------------------------------------------------------
                | UPDATE DATA PERBAIKAN
                |--------------------------------------------------------------------------
                */

                $perbaikan->update([
                    'bukti_foto' =>
                        $foto,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI KE ADMIN
        |--------------------------------------------------------------------------
        */

        $push = app(
            PushNotificationService::class
        );

        $admins = User::where(
            'role',
            'admin'
        )->get();

        foreach ($admins as $admin) {

            $setting =
                $admin->notificationSetting;

            /*
            |--------------------------------------------------------------------------
            | Jika setting belum ada,
            | notifikasi tetap dikirim.
            |--------------------------------------------------------------------------
            */

            if (
                !$setting ||
                $setting->laporan_teknisi
            ) {

                $push->send(
                    $admin,

                    'Laporan Teknisi Baru',

                    'Laporan ' .
                    $laporan->id_laporan .
                    ' telah dikirim dan menunggu approval Admin.',

                    'laporan_teknisi',

                    url(
                        '/laporan-detail/' .
                        $laporan->id
                    ),

                    'laporan_teknisi',

                    $laporan->id
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE LAPORAN TEKNISI
        |--------------------------------------------------------------------------
        */

        return redirect(
            '/laporan-teknisi'
        )
        ->with(
            'success',
            'Laporan berhasil dikirim dan penugasan dinyatakan selesai.'
        );
    }
}