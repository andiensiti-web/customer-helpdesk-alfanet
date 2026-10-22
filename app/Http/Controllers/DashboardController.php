<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Models\LaporanGangguan;
use App\Models\Tagihan;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        /* DATA PELANGGAN */
        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::where('status', 'aktif')->count();
        $pelangganTidakAktif = Pelanggan::where('status', 'nonaktif')->count();
        $pelangganIsolir = Pelanggan::where('status', 'isolir')->count();

        
        /* DATA GANGGUAN DAN PENUGASAN TEKNISI */
        $gangguanBaru = LaporanGangguan::where('status', 'baru')->count();

        $dalamProses = \App\Models\PenugasanTeknisi::whereIn('status', [
            'ditugaskan',
            'sedang_ditangani',
            'sedang ditangani',
            'dalam_proses',
            'diproses',
        ])->count();


        /* DATA TAGIHAN */
        $belumBayar = Tagihan::where('status', 'belum_bayar')->count();

        /* PEMASUKAN BULAN INI DAN BULAN KEMARIN */
        $bulanIni = now();
        $bulanKemarin = now()->copy()->subMonth();

        $pemasukanBulanIni = Pembayaran::where('status', 'terverifikasi')
            ->whereMonth('tanggal_pembayaran', $bulanIni->month)
            ->whereYear('tanggal_pembayaran', $bulanIni->year)
            ->sum('jumlah');

        $pemasukanBulanKemarin = Pembayaran::where('status', 'terverifikasi')
            ->whereMonth('tanggal_pembayaran', $bulanKemarin->month)
            ->whereYear('tanggal_pembayaran', $bulanKemarin->year)
            ->sum('jumlah');

        /* STATUS PELANGGAN UNTUK GRAFIK */
        $statusPelanggan = [
            Pelanggan::where('status', 'aktif')->count(),
            Pelanggan::where('status', 'menunggak')->count(),
            Pelanggan::where('status', 'nonaktif')->count(),
            Pelanggan::where('status', 'isolir')->count(),
        ];

        /* DATA PEMBAYARAN PER BULAN */
        $dataPembayaran = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $dataPembayaran[] = Pembayaran::where('status', 'terverifikasi')
                ->whereMonth('tanggal_pembayaran', $bulan)
                ->whereYear('tanggal_pembayaran', now()->year)
                ->sum('jumlah');
        }

        /* NOTIFIKASI */
        $notifikasi = Notifikasi::latest()->take(5)->get();
        $jumlahNotifikasi = Notifikasi::where('dibaca', false)->count();

        /* KIRIM DATA KE DASHBOARD */
        return view('admin.dashboard', compact(
            'totalPelanggan',
            'pelangganAktif',
            'pelangganTidakAktif',
            'pelangganIsolir',
            'gangguanBaru',
            'dalamProses',
            'belumBayar',
            'pemasukanBulanIni',
            'pemasukanBulanKemarin',
            'statusPelanggan',
            'dataPembayaran',
            'notifikasi',
            'jumlahNotifikasi'
        ));
    }

    public function notifikasi($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->update(['dibaca' => true]);

        if ($notifikasi->tipe === 'laporan_teknisi') {
            return redirect('/laporan-detail/' . $notifikasi->referensi_id);
        }

        if ($notifikasi->tipe === 'pembayaran') {
            return redirect('/detail-pembayaran/' . $notifikasi->referensi_id);
        }

        if ($notifikasi->tipe === 'tagihan') {
            return redirect('/detail-tagihan/' . $notifikasi->referensi_id);
        }

        return redirect('/dashboard');
    }
}
