<?php
namespace App\Http\Controllers;

use App\Models\JadwalKunjungan;
use App\Models\PenugasanTeknisi;
use App\Models\Perbaikan;
use Illuminate\Support\Facades\Auth;

class TeknisiDashboardController extends Controller
{
    public function index()
    {
        $teknisi = Auth::user()->teknisi;

        $totalTugas = PenugasanTeknisi::where('teknisi_id', $teknisi->id)->count();

        $tugasHariIni = PenugasanTeknisi::where('teknisi_id', $teknisi->id)
            ->whereDate('tanggal_penugasan', today())
            ->count();

        $sedangDitangani = PenugasanTeknisi::where('teknisi_id', $teknisi->id)
            ->whereIn('status', ['diproses', 'dalam_proses'])
            ->count();

        $tugasSelesai = PenugasanTeknisi::where('teknisi_id', $teknisi->id)
            ->where('status', 'selesai')
            ->whereBetween('tanggal_penugasan', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();

        $jadwalHariIni = JadwalKunjungan::with('pelanggan.user')
            ->where('teknisi_id', $teknisi->id)
            ->whereDate('tanggal_kunjungan', today())
            ->orderBy('jam_kunjungan')
            ->get();

        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = PenugasanTeknisi::with([
                'tiketGangguan.pelanggan.user',
                'tiketGangguan.laporanGangguan'
            ])
            ->where('teknisi_id', $teknisi->id)
            ->whereDate('tanggal_penugasan', today())
            ->orderBy('tanggal_penugasan')
            ->get();
        }

        $statusPerbaikan = [
            'belum_mulai' => PenugasanTeknisi::where('teknisi_id', $teknisi->id)
                ->whereIn('status', ['ditugaskan', 'menunggu', 'belum_dikerjakan'])
                ->count(),
            'dikerjakan' => PenugasanTeknisi::where('teknisi_id', $teknisi->id)
                ->whereIn('status', ['diproses', 'dalam_proses'])
                ->count(),
            'selesai' => PenugasanTeknisi::where('teknisi_id', $teknisi->id)
                ->where('status', 'selesai')
                ->count(),
        ];

        return view('teknisi.dashboard-teknisi', compact(
            'teknisi',
            'totalTugas',
            'tugasHariIni',
            'sedangDitangani',
            'tugasSelesai',
            'jadwalHariIni',
            'statusPerbaikan'
        ));
    }
}