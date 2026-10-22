<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

class PelangganDashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();


        $pelanggan = $user->pelanggan;


        if (!$pelanggan) {

            abort(404, 'Data pelanggan tidak ditemukan.');

        }



        $tagihanTerakhir = $pelanggan
            ->tagihan()
            ->latest()
            ->first();



        $jumlahTagihan = $pelanggan
            ->tagihan()
            ->count();



        $jumlahBelumBayar = $pelanggan
            ->tagihan()
            ->where(
                'status',
                'belum_bayar'
            )
            ->count();



        $pembayaranTerakhir = $pelanggan
            ->pembayaran()
            ->latest()
            ->first();



        $notifikasi = Notifikasi::where(
            'user_id',
            $user->id
        )
        ->latest()
        ->take(5)
        ->get();

        $laporanTerbaru = $pelanggan
        ->laporanGangguan()
        ->latest()
        ->first();

        return view(
            'pelanggan.dashboard-pelanggan',
            compact(
                'pelanggan',
                'tagihanTerakhir',
                'jumlahTagihan',
                'jumlahBelumBayar',
                'pembayaranTerakhir',
                'notifikasi',
                'laporanTerbaru'
            )
        );
    }

}