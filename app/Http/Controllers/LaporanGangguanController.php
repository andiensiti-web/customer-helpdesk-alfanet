<?php

namespace App\Http\Controllers;

use App\Models\LaporanGangguan;
use App\Models\User;
use App\Services\MlPriorityService;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanGangguanController extends Controller
{
    public function __construct(
        private MlPriorityService $mlPriorityService
    ) {
    }

    public function create()
    {
        $pelanggan = Auth::user()->pelanggan;

        if (!$pelanggan) {
            abort(404, 'Data pelanggan tidak ditemukan.');
        }

        $pelanggan->load('paket');

        $laporans = LaporanGangguan::with([
            'tiketGangguan.penugasan.perbaikan'
        ])
        ->where(
            'pelanggan_id',
            $pelanggan->id
        )
        ->latest('id')
        ->get();

        return view(
            'pelanggan.laporan-gangguan',
            compact(
                'pelanggan',
                'laporans'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_gangguan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $pelanggan = Auth::user()->pelanggan;

        if (!$pelanggan) {
            abort(404, 'Data pelanggan tidak ditemukan.');
        }

        
        $prioritas = $this->mlPriorityService->predict(
            $request->jenis_gangguan,
            $request->deskripsi
        );

      
        $last = LaporanGangguan::latest('id')->first();

        $nomor = $last
            ? $last->id + 1
            : 1;

        $laporan = LaporanGangguan::create([
            'id_laporan' => 'LPR-' . str_pad(
                $nomor,
                4,
                '0',
                STR_PAD_LEFT
            ),
            'pelanggan_id' => $pelanggan->id,
            'jenis_gangguan' => $request->jenis_gangguan,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $prioritas,
            'status' => 'baru',
            'tanggal_laporan' => now(),
        ]);

        $admins = User::where(
            'role',
            'admin'
        )->get();

        $push = app(
            PushNotificationService::class
        );

        foreach ($admins as $admin) {

            $setting = $admin->notificationSetting;

            if (
                !$setting ||
                $setting->gangguan
            ) {
                $push->send(
                    $admin,
                    'Laporan Gangguan Baru',
                    'Laporan ' . $laporan->id_laporan . ' telah dibuat pelanggan.',
                    'gangguan',
                    url('/gangguan-detail/' . $laporan->id),
                    'gangguan',
                    $laporan->id
                );
            }
        }

        return redirect(
            '/laporan-gangguan'
        )->with(
            'success',
            'Laporan gangguan berhasil dikirim.'
        );
    }

    public function index()
    {
        $laporans = LaporanGangguan::with([
            'pelanggan.user',
            'tiketGangguan.penugasan'
        ])
        ->whereDoesntHave(
            'tiketGangguan.penugasan.laporanTeknisi',
            function ($query) {
                $query->where('status', 'disetujui');
            }
        )
        ->latest('id')
        ->get();

        return view(
            'admin.gangguan-admin',
            compact('laporans')
        );
    }

    public function show($id)
    {
        $laporan = LaporanGangguan::with(
            'pelanggan.user'
        )
        ->findOrFail($id);

        return view(
            'admin.gangguan-detail',
            compact('laporan')
        );
    }

    public function verify($id)
    {
        $laporan = LaporanGangguan::with(
            'pelanggan.user'
        )
        ->findOrFail($id);

        return view(
            'admin.gangguan-verifikasi',
            compact('laporan')
        );
    }

    public function approve($id)
    {
        $laporan = LaporanGangguan::findOrFail($id);

        $laporan->update([
            'status' => 'diverifikasi',
        ]);

        return redirect(
            '/tambah-tiket/' . $laporan->id
        )->with(
            'success',
            'Laporan berhasil diverifikasi.'
        );
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $laporan = LaporanGangguan::with(
            'pelanggan.user'
        )->findOrFail($id);

        $laporan->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan,
        ]);
      
        $pelangganUser = $laporan->pelanggan?->user;

        if ($pelangganUser) {

            app(
                PushNotificationService::class
            )->send(
                $pelangganUser,
                'Laporan Ditolak',
                'Laporan ' . $laporan->id_laporan .
                ' ditolak Admin. Catatan: ' .
                $laporan->catatan,
                'gangguan',
                url('/laporan-gangguan'),
                'gangguan',
                $laporan->id
            );
        }

        return redirect(
            '/gangguan-detail/' . $id
        )->with(
            'success',
            'Laporan berhasil ditolak.'
        );
    }
}