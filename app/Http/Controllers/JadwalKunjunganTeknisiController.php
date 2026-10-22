<?php

namespace App\Http\Controllers;

use App\Models\JadwalKunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKunjunganTeknisiController extends Controller
{
    public function index(Request $request)
    {
        $teknisi = Auth::user()->teknisi;

        $query = JadwalKunjungan::with([
            'pelanggan.user',
            'pelanggan.paket',
            'penugasan.tiketGangguan.laporanGangguan'
        ])->where(
            'teknisi_id',
            $teknisi->id
        );

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('pelanggan', function ($q) use ($search) {

                    $q->where(
                        'id_pelanggan',
                        'like',
                        '%'.$search.'%'
                    )
                    ->orWhereHas('user', function ($q) use ($search) {

                        $q->where(
                            'name',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'telepon',
                            'like',
                            '%'.$search.'%'
                        );

                    });

                })
                ->orWhereHas(
                    'penugasan.tiketGangguan',
                    function ($q) use ($search) {

                        $q->where(
                            'id_tiket',
                            'like',
                            '%'.$search.'%'
                        );

                    }
                );

            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate(
                'tanggal_kunjungan',
                $request->tanggal
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $jadwals = $query
            ->orderBy('tanggal_kunjungan')
            ->orderBy('jam_kunjungan')
            ->get();

        $totalJadwal = $jadwals->count();

        $jadwalSelesai = $jadwals
            ->where('status', 'selesai')
            ->count();

        $jadwalMenunggu = $jadwals
            ->whereIn('status', [
                'dijadwalkan',
                'menunggu',
                'belum_dikerjakan'
            ])
            ->count();

        $jadwalDalamProses = $jadwals
            ->whereIn('status', [
                'berlangsung',
                'diproses',
                'dalam_proses',
                'dalam_perjalanan'
            ])
            ->count();

        return view(
            'teknisi.jadwal-kunjungan',
            compact(
                'teknisi',
                'jadwals',
                'totalJadwal',
                'jadwalSelesai',
                'jadwalMenunggu',
                'jadwalDalamProses'
            )
        );
    }
}