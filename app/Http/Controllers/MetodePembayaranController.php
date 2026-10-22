<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $metodePembayaran = MetodePembayaran::orderBy('id', 'asc')->get();

        return view(
            'admin.pengaturan-pembayaran',
            compact('metodePembayaran')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:100',
            'jenis' => 'required|in:bank,ewallet',
            'nomor_rekening' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        MetodePembayaran::create([
            'nama_metode' => $request->nama_metode,
            'jenis' => $request->jenis,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Metode pembayaran berhasil ditambahkan.'
        );
    }

    public function update(Request $request, $id)
    {
        $metode = MetodePembayaran::findOrFail($id);

        $request->validate([
            'nama_metode' => 'required|string|max:100',
            'jenis' => 'required|in:bank,ewallet',
            'nomor_rekening' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $metode->nama_metode = $request->nama_metode;
        $metode->jenis = $request->jenis;
        $metode->nomor_rekening = $request->nomor_rekening;
        $metode->atas_nama = $request->atas_nama;
        $metode->status = $request->status;

        $metode->save();

        return back()->with(
            'success',
            'Metode pembayaran berhasil diperbarui.'
        );
    }

    public function destroy($id)
    {
        $metode = MetodePembayaran::findOrFail($id);

        $metode->delete();

        return back()->with(
            'success',
            'Metode pembayaran berhasil dihapus.'
        );
    }
}