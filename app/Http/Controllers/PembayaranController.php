<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\PushNotificationService;

class PembayaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $pembayaran = Pembayaran::with([
            'pelanggan.user',
            'tagihan'
        ])
            ->latest()
            ->get();

        return view(
            'admin.pembayaran-admin',
            compact('pembayaran')
        );
    }


    public function create()
    {
        $pelanggans = Pelanggan::with('user')
            ->where('status', 'aktif')
            ->get();

        $tagihans = Tagihan::where(
            'status',
            'belum_bayar'
        )
            ->latest()
            ->get();

        return view(
            'admin.catat-pembayaran',
            compact(
                'pelanggans',
                'tagihans'
            )
        );
    }


    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'pelanggan.user',
            'tagihan',
            'verifier'
        ])
            ->findOrFail($id);

        return view(
            'admin.detail-pembayaran',
            compact('pembayaran')
        );
    }


    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'
                => 'required|exists:pelanggans,id',

            'tagihan_id'
                => 'required|exists:tagihan,id',

            'jumlah'
                => 'required|numeric|min:1',

            'metode'
                => 'required|in:tunai,transfer,e_wallet',

            'tanggal_pembayaran'
                => 'required|date',

            'catatan_pelanggan'
                => 'nullable|string',
        ]);


        $tagihan = Tagihan::where(
            'id',
            $request->tagihan_id
        )
            ->where(
                'pelanggan_id',
                $request->pelanggan_id
            )
            ->firstOrFail();


        $last = Pembayaran::latest('id')->first();

        $nomor = $last
            ? $last->id + 1
            : 1;


        $pembayaran = Pembayaran::create([

            'kode_pembayaran' =>
                'PAY-' .
                str_pad(
                    $nomor,
                    4,
                    '0',
                    STR_PAD_LEFT
                ),

            'tagihan_id' =>
                $tagihan->id,

            'pelanggan_id' =>
                $request->pelanggan_id,

            'jumlah' =>
                $request->jumlah,

            'metode' =>
                $request->metode,

            'tanggal_pembayaran' =>
                $request->tanggal_pembayaran,

            'catatan_pelanggan' =>
                $request->catatan_pelanggan,

            'status' =>
                'menunggu_verifikasi',
        ]);


        $admins = User::where(
            'role',
            'admin'
        )->get();


        foreach ($admins as $admin) {

            Notifikasi::create([

                'user_id' =>
                    $admin->id,

                'judul' =>
                    'Pembayaran Baru',

                'pesan' =>
                    'Pembayaran ' .
                    $pembayaran->kode_pembayaran .
                    ' menunggu verifikasi.',

                'tipe' =>
                    'pembayaran',

                'referensi_tipe' =>
                    'pembayaran',

                'referensi_id' =>
                    $pembayaran->id,

                'dibaca' =>
                    false,
            ]);
        }


        return redirect('/pembayaran-admin')
            ->with(
                'success',
                'Pembayaran berhasil ditambahkan.'
            );
    }


    public function verify($id)
    {
        $pembayaran = Pembayaran::with([
            'tagihan',
            'pelanggan.user'
        ])
            ->findOrFail($id);


        $pembayaran->update([

            'status' =>
                'terverifikasi',

            'diverifikasi_oleh' =>
                Auth::id(),

            'tanggal_verifikasi' =>
                now(),
        ]);


        $pembayaran->tagihan->update([
            'status' =>
                'lunas'
        ]);


        $pelangganUser =
            $pembayaran->pelanggan?->user;


        if ($pelangganUser) {

            Notifikasi::create([

                'user_id' =>
                    $pelangganUser->id,

                'judul' =>
                    'Pembayaran Berhasil',

                'pesan' =>
                    'Pembayaran ' .
                    $pembayaran->kode_pembayaran .
                    ' telah diverifikasi.',

                'tipe' =>
                    'pembayaran',

                'referensi_tipe' =>
                    'pembayaran',

                'referensi_id' =>
                    $pembayaran->id,

                'dibaca' =>
                    false,
            ]);
        }


        return redirect(
            '/detail-pembayaran/' . $id
        )
            ->with(
                'success',
                'Pembayaran berhasil diverifikasi.'
            );
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' =>
                'required|string'
        ]);


        $pembayaran =
            Pembayaran::findOrFail($id);


        $pembayaran->update([

            'status' =>
                'ditolak',

            'catatan_verifikasi' =>
                $request->catatan,

        ]);


        return redirect('/pembayaran-admin')
            ->with(
                'success',
                'Pembayaran berhasil ditolak.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PELANGGAN
    |--------------------------------------------------------------------------
    */

    public function createPelanggan($id)
    {
        $pelanggan =
            Auth::user()->pelanggan;


        $tagihan = Tagihan::with('paket')
            ->where(
                'pelanggan_id',
                $pelanggan->id
            )
            ->findOrFail($id);


        $riwayatPembayaran =
            Pembayaran::where(
                'pelanggan_id',
                $pelanggan->id
            )
                ->latest()
                ->get();


        $pembayaranTerakhir =
            Pembayaran::where(
                'tagihan_id',
                $tagihan->id
            )
                ->latest()
                ->first();


        /*
        |--------------------------------------------------------------------------
        | METODE PEMBAYARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $metodePembayaran =
            MetodePembayaran::where(
                'status',
                'aktif'
            )
                ->orderBy('jenis')
                ->orderBy('nama_metode')
                ->get();


        return view(
            'pelanggan.pembayaran-pelanggan',
            compact(
                'pelanggan',
                'tagihan',
                'riwayatPembayaran',
                'pembayaranTerakhir',
                'metodePembayaran'
            )
        );
    }


    public function storePelanggan(Request $request)
    {
        $pelanggan =
            Auth::user()->pelanggan;


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'tagihan_id' =>
                'required|exists:tagihan,id',

            'metode' =>
                'required|string|max:100',

            'tanggal_pembayaran' =>
                'required|date',

            'bukti_pembayaran' =>
                'required|image|mimes:jpg,jpeg,png|max:5120',

        ]);


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN TAGIHAN MILIK PELANGGAN
        |--------------------------------------------------------------------------
        */

        $tagihan = Tagihan::where(
            'pelanggan_id',
            $pelanggan->id
        )
            ->findOrFail(
                $request->tagihan_id
            );


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN METODE MASIH AKTIF
        |--------------------------------------------------------------------------
        */

        $metodePembayaran =
            MetodePembayaran::where(
                'nama_metode',
                $request->metode
            )
                ->where(
                    'status',
                    'aktif'
                )
                ->first();


        if (!$metodePembayaran) {

            return back()
                ->withErrors([
                    'metode' =>
                        'Metode pembayaran tidak tersedia atau sudah dinonaktifkan.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | BUAT FOLDER PELANGGAN
        |--------------------------------------------------------------------------
        */

        $namaFolder = Str::slug(

            $pelanggan->user->name
                ?? $pelanggan->nama
                ?? 'pelanggan'

        );


        $folderPelanggan =
            'pelanggan/' .
            $pelanggan->id_pelanggan .
            '_' .
            $namaFolder;


        $folderPembayaran =
            $folderPelanggan .
            '/pembayaran';


        /*
        |--------------------------------------------------------------------------
        | UPLOAD BUKTI PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $file =
            $request->file(
                'bukti_pembayaran'
            );


        $namaFile =
            now('Asia/Jakarta')->format(
                'Ymd-His'
            )
            . '.'
            . $file->getClientOriginalExtension();


        $bukti =
            $file->storeAs(
                $folderPembayaran,
                $namaFile,
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | NOMOR PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $last =
            Pembayaran::latest('id')->first();


        $nomor =
            $last
                ? $last->id + 1
                : 1;


        /*
        |--------------------------------------------------------------------------
        | SIMPAN PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $pembayaran =
            Pembayaran::create([

                'kode_pembayaran' =>
                    'PAY-' .
                    str_pad(
                        $nomor,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ),

                'tagihan_id' =>
                    $tagihan->id,

                'pelanggan_id' =>
                    $pelanggan->id,

                'jumlah' =>
                    $tagihan->jumlah,

                'metode' =>
                    $metodePembayaran->nama_metode,

                'tanggal_pembayaran' =>
                    $request->tanggal_pembayaran,

                'bukti_pembayaran' =>
                    $bukti,

                'status' =>
                    'menunggu_verifikasi',
            ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI ADMIN
        |--------------------------------------------------------------------------
        */

        $admins =
            User::where(
                'role',
                'admin'
            )->get();


        foreach ($admins as $admin) {

            app(
                PushNotificationService::class
            )->send(

                $admin,

                'Pembayaran Baru',

                'Pelanggan ' .
                (
                    $pelanggan->user->name
                    ?? $pelanggan->nama
                ) .
                ' telah mengirim pembayaran ' .
                $pembayaran->kode_pembayaran .
                '.',

                'pembayaran',

                url(
                    '/admin/detail-pembayaran/' .
                    $pembayaran->id
                ),

                'pembayaran',

                $pembayaran->id
            );


            Notifikasi::create([

                'user_id' =>
                    $admin->id,

                'judul' =>
                    'Pembayaran Baru',

                'pesan' =>
                    'Ada pembayaran baru dari pelanggan.',

                'tipe' =>
                    'pembayaran',

                'referensi_tipe' =>
                    'pembayaran',

                'referensi_id' =>
                    $pembayaran->id,

                'dibaca' =>
                    false,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS TAGIHAN
        |--------------------------------------------------------------------------
        */

        $tagihan->update([
            'status' =>
                'menunggu_verifikasi'
        ]);


        return redirect(
            '/tagihan-pelanggan'
        )
            ->with(
                'success',
                'Pembayaran berhasil dikirim. Menunggu verifikasi admin.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function riwayat()
    {
        $pelanggan =
            Auth::user()->pelanggan;


        $riwayatPembayaran =
            Pembayaran::with('tagihan')
                ->where(
                    'pelanggan_id',
                    $pelanggan->id
                )
                ->latest()
                ->get();


        return view(
            'pelanggan.riwayat-pembayaran',
            compact('riwayatPembayaran')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL PEMBAYARAN PELANGGAN
    |--------------------------------------------------------------------------
    */

    public function detailPelanggan($id)
    {
        $pelanggan =
            Auth::user()->pelanggan;


        $pembayaran =
            Pembayaran::with([
                'tagihan',
                'pelanggan.user'
            ])
                ->where(
                    'pelanggan_id',
                    $pelanggan->id
                )
                ->findOrFail($id);


        return view(
            'pelanggan.detail-pembayaran',
            compact('pembayaran')
        );
    }

    public function invoicePelanggan($id)
    {
        $pelanggan = Auth::user()->pelanggan;

        $pembayaran = Pembayaran::with([
            'tagihan.paket',
            'pelanggan.user'
        ])
            ->where('pelanggan_id', $pelanggan->id)
            ->findOrFail($id);

        if ($pembayaran->status !== 'terverifikasi') {
            abort(404);
        }

        return view(
            'pelanggan.invoice-pembayaran',
            compact('pembayaran', 'pelanggan')
        );
    }

    public function detailAdmin($id)
    {
        $pembayaran =
            Pembayaran::with([
                'tagihan',
                'pelanggan.user'
            ])
                ->findOrFail($id);


        return view(
            'admin.detail-pembayaran',
            compact('pembayaran')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function print($id)
    {
        $pembayaran =
            Pembayaran::with([
                'pelanggan.user'
            ])
                ->findOrFail($id);


        return view(
            'admin.print-pembayaran',
            compact('pembayaran')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function verifikasi($id)
    {
        $pembayaran =
            Pembayaran::with([
                'tagihan',
                'pelanggan.user'
            ])
                ->findOrFail($id);


        $pembayaran->update([
            'status' =>
                'terverifikasi'
        ]);


        $pembayaran->tagihan->update([
            'status' =>
                'lunas'
        ]);


        $user =
            $pembayaran->pelanggan?->user;


        if ($user) {

            app(
                PushNotificationService::class
            )->send(

                $user,

                'Pembayaran Berhasil Diverifikasi',

                'Pembayaran ' .
                $pembayaran->kode_pembayaran .
                ' telah diverifikasi oleh Admin.',

                'pembayaran',

                url('/riwayat-pembayaran'),

                'pembayaran',

                $pembayaran->id
            );
        }


        return redirect(
            '/pembayaran-admin'
        )
            ->with(
                'success',
                'Pembayaran berhasil diverifikasi.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PERBAIKI PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function perbaikiPembayaran($id)
    {
        $pembayaran =
            Pembayaran::findOrFail($id);


        return view(
            'pelanggan.perbaiki-pembayaran',
            compact('pembayaran')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD ULANG BUKTI PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function updateBuktiPembayaran(
        Request $request,
        $id
    ) {

        $request->validate([

            'bukti_pembayaran' =>
                'required|image|mimes:jpg,jpeg,png|max:5120'

        ]);


        $pembayaran =
            Pembayaran::with(
                'pelanggan.user'
            )
                ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Pastikan milik pelanggan yang sedang login
        |--------------------------------------------------------------------------
        */

        $pelanggan =
            Auth::user()->pelanggan;


        if (
            !$pelanggan ||
            $pembayaran->pelanggan_id != $pelanggan->id
        ) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus file lama
        |--------------------------------------------------------------------------
        */

        if (
            $pembayaran->bukti_pembayaran &&
            Storage::disk('public')->exists(
                $pembayaran->bukti_pembayaran
            )
        ) {

            Storage::disk('public')->delete(
                $pembayaran->bukti_pembayaran
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Folder pelanggan
        |--------------------------------------------------------------------------
        */

        $namaFolder =
            Str::slug(

                $pelanggan->user->name
                    ?? $pelanggan->nama
                    ?? 'pelanggan'

            );


        $folderPelanggan =
            'pelanggan/' .
            $pelanggan->id_pelanggan .
            '_' .
            $namaFolder;


        $folderPembayaran =
            $folderPelanggan .
            '/pembayaran';


        /*
        |--------------------------------------------------------------------------
        | Upload file baru
        |--------------------------------------------------------------------------
        */

        $file =
            $request->file(
                'bukti_pembayaran'
            );


        $namaFile =
            now('Asia/Jakarta')->format(
                'Ymd-His'
            )
            . '.'
            . $file->getClientOriginalExtension();


        $path =
            $file->storeAs(
                $folderPembayaran,
                $namaFile,
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | Update database
        |--------------------------------------------------------------------------
        */

        $pembayaran->update([

            'bukti_pembayaran' =>
                $path,

            'status' =>
                'menunggu_verifikasi'

        ]);


        return redirect(
            '/riwayat-pembayaran'
        )
            ->with(
                'success',
                'Bukti pembayaran berhasil dikirim ulang.'
            );
    }
}