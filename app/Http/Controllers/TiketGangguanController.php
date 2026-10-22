<?php

namespace App\Http\Controllers;

use App\Models\LaporanGangguan;
use App\Models\TiketGangguan;
use Illuminate\Http\Request;

class TiketGangguanController extends Controller
{
    public function create($id)
    {
        $laporan = LaporanGangguan::with('pelanggan.user')->findOrFail($id);

        return view('admin.tambah-tiket', compact('laporan'));
    }

    public function store(Request $request, $id)
    {
        $laporan = LaporanGangguan::findOrFail($id);

        $last = TiketGangguan::latest('id')->first();
        $nomor = $last ? $last->id + 1 : 1;

        TiketGangguan::create([
            'id_tiket' => 'TKT-' . str_pad($nomor, 4, '0', STR_PAD_LEFT),
            'laporan_gangguan_id' => $laporan->id,
            'pelanggan_id' => $laporan->pelanggan_id,
            'jenis_gangguan' => $laporan->jenis_gangguan,
            'prioritas' => $laporan->prioritas,
            'status' => 'menunggu_teknisi',
            'tanggal_tiket' => now(),
        ]);

        return redirect('/penugasan-teknisi')
            ->with('success', 'Tiket gangguan berhasil dibuat.');
    }
}