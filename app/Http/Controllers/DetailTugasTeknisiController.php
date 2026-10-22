<?php
namespace App\Http\Controllers;

use App\Models\JadwalKunjungan;
use App\Models\PenugasanTeknisi;
use App\Models\Perbaikan;
use Illuminate\Support\Facades\Auth;

class DetailTugasTeknisiController extends Controller
{
    public function show($id)
    {
        $teknisi = Auth::user()->teknisi;

        $tugas = PenugasanTeknisi::with([
            'tiketGangguan.pelanggan.user',
            'tiketGangguan.pelanggan.paket',
            'tiketGangguan.laporanGangguan',
            'teknisi',
            'perbaikan'
        ])
        ->where('teknisi_id', $teknisi->id)
        ->findOrFail($id);

        $jadwalKunjungan = JadwalKunjungan::where('penugasan_teknisi_id', $tugas->id)
            ->where('teknisi_id', $teknisi->id)
            ->first();

        return view('teknisi.detail-tugas', compact(
            'tugas',
            'jadwalKunjungan'
        ));
    }

    public function mulai($id)
    {
    $teknisi = Auth::user()->teknisi;

    $tugas = PenugasanTeknisi::with([
        'tiketGangguan.laporanGangguan',
        'tiketGangguan.pelanggan'
    ])
    ->where('teknisi_id', $teknisi->id)
    ->findOrFail($id);

    if ($tugas->status === 'selesai') {
        return redirect('/detail-tugas/'.$id)
            ->with('error', 'Tugas ini sudah selesai.');
    }

    $laporan = $tugas->tiketGangguan->laporanGangguan;

    Perbaikan::firstOrCreate(
        [
            'penugasan_teknisi_id' => $tugas->id,
        ],
        [
            'teknisi_id' => $teknisi->id,
            'pelanggan_id' => $tugas->tiketGangguan->pelanggan_id,
            'tanggal_perbaikan' => today(),
            'keluhan' => $laporan?->deskripsi,
            'status' => 'dikerjakan',
        ]
    );

    $tugas->update([
        'status' => 'diproses',
    ]);

    return redirect('/perbaikan-teknisi/'.$id)
        ->with('success', 'Perbaikan dimulai.');
    }
}