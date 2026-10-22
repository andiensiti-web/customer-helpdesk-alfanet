<?php

namespace App\Http\Controllers;

use App\Models\PenugasanTeknisi;
use App\Models\TiketGangguan;
use App\Models\Teknisi;
use App\Models\JadwalKunjungan;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class PenugasanTeknisiController extends Controller
{
    public function index()
    {
    $tiket = TiketGangguan::with([
        'pelanggan.user',
        'penugasan.teknisi',
        'penugasan.laporanTeknisi',
        'penugasan.perbaikan'
    ])
    ->latest()
    ->get();

    foreach ($tiket as $item) {
        $penugasan = $item->penugasan;
        if (!$penugasan) {continue;}
        if ($penugasan->laporanTeknisi) {$penugasan->status = 'selesai';}
        elseif ($penugasan->perbaikan && $penugasan->perbaikan->status === 'selesai') {
            $penugasan->status = 'selesai';
        }
    }

    return view(
        'admin.penugasan-teknisi',
        compact('tiket')
    );
    }

    public function create($id)
    {
        $tiket = TiketGangguan::with([
            'pelanggan.user',
            'laporanGangguan',
            'penugasan'
        ])
        ->findOrFail($id);

        if ($tiket->penugasan) {
            return redirect(
                '/detail-teknisi/' . $tiket->id
            );
        }

        $teknisis = Teknisi::where(
            'status',
            'aktif'
        )
        ->orderBy('nama')
        ->get();

        return view(
            'admin.detail-penugasan',
            compact(
                'tiket',
                'teknisis'
            )
        );
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'teknisi_id' => 'required|exists:teknisi,id',
            'tanggal_penugasan' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $tiket = TiketGangguan::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PENUGASAN
        |--------------------------------------------------------------------------
        */

        $penugasan = PenugasanTeknisi::updateOrCreate(
            [
                'tiket_gangguan_id' => $tiket->id,
            ],
            [
                'teknisi_id' => $request->teknisi_id,
                'tanggal_penugasan' => $request->tanggal_penugasan,
                'status' => 'ditugaskan',
                'catatan' => $request->catatan,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS TIKET
        |--------------------------------------------------------------------------
        */

        $tiket->update([
            'status' => 'ditugaskan',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BUAT JADWAL KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        $jadwal = JadwalKunjungan::updateOrCreate(
            [
                'penugasan_teknisi_id' => $penugasan->id,
            ],
            [
                'teknisi_id' => $penugasan->teknisi_id,
                'pelanggan_id' => $tiket->pelanggan_id,
                'tanggal_kunjungan' => $request->tanggal_penugasan,
                'status' => 'dijadwalkan',
                'catatan' => $request->catatan,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI TEKNISI
        |--------------------------------------------------------------------------
        */

        $penugasan->load(
            'teknisi.user'
        );

        $teknisiUser = $penugasan
            ->teknisi
            ?->user;

        if ($teknisiUser) {

            $push = app(
                PushNotificationService::class
            );

            $setting = $teknisiUser
                ->notificationSetting;

            /*
            |--------------------------------------------------------------------------
            | NOTIFIKASI TUGAS BARU
            |--------------------------------------------------------------------------
            */

            if (
                !$setting ||
                $setting->tugas_teknisi
            ) {
                $push->send(
                    $teknisiUser,
                    'Tugas Baru',
                    'Anda mendapatkan tugas teknisi baru.',
                    'penugasan_teknisi',
                    url('/detail-tugas/' . $penugasan->id),
                    'penugasan_teknisi',
                    $penugasan->id
                );
            }

            /*
            |--------------------------------------------------------------------------
            | NOTIFIKASI JADWAL KUNJUNGAN
            |--------------------------------------------------------------------------
            */

            if (
                !$setting ||
                $setting->jadwal_kunjungan
            ) {
                $push->send(
                    $teknisiUser,
                    'Jadwal Kunjungan Baru',
                    'Anda memiliki jadwal kunjungan baru.',
                    'jadwal_kunjungan',
                    url('/jadwal-kunjungan'),
                    'jadwal_kunjungan',
                    $jadwal->id
                );
            }
        }

        return redirect(
            '/penugasan-teknisi'
        )->with(
            'success',
            'Teknisi berhasil ditugaskan.'
        );
    }

    public function detail($id)
    {
        $tiket = TiketGangguan::with([
            'pelanggan.user',
            'penugasan.teknisi',
            'penugasan.laporanTeknisi',
            'penugasan.perbaikan',
            'laporanGangguan'
        ])
        ->findOrFail($id);
        $penugasan = $tiket->penugasan;
        if ($penugasan) {    
        if ($penugasan->status === 'selesai') {
            $tiket->update([
                'status' => 'selesai',
            ]);
        }
       elseif (
            $penugasan->perbaikan &&
            $penugasan->perbaikan->status === 'selesai'
        ) {
            $tiket->update([
                'status' => 'selesai',
            ]);
        }
       elseif ($penugasan->laporanTeknisi) {
            $tiket->update([
                'status' => 'selesai',
            ]);
        }
    }
        return view(
            'admin.detail-teknisi',
            compact('tiket')
        );
    }
}