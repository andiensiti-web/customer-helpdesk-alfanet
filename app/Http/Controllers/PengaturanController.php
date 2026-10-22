<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationSetting;
use App\Models\Paket;
use App\Models\PengaturanPerusahaan;

class PengaturanController extends Controller
{
    public function index()
    {
        $perusahaan = PengaturanPerusahaan::first();

        return view('admin.pengaturan', compact('perusahaan'));
    }

    public function akun()
    {
        $user = Auth::user();

        return view(
            'admin.pengaturan-akun',
            compact('user')
        );
    }

    public function notifikasi()
    {
        $user = Auth::user();

        $setting = NotificationSetting::firstOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'laporan_teknisi' => true,
                'pembayaran' => true,
                'gangguan' => true,
                'penugasan' => true,
            ]
        );

        return view(
            'admin.pengaturan-notifikasi',
            compact('setting')
        );
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $setting = NotificationSetting::firstOrCreate(
            [
                'user_id' => $user->id
            ]
        );

        $setting->update([
            'laporan_teknisi' => $request->has('laporan_teknisi'),
            'pembayaran' => $request->has('pembayaran'),
            'gangguan' => $request->has('gangguan'),
            'penugasan' => $request->has('penugasan'),
        ]);

        return back()->with(
            'success',
            'Pengaturan notifikasi berhasil diperbarui.'
        );
    }

    public function paket()
    {
        $pakets = Paket::orderBy('id', 'asc')->get();

        return view(
            'admin.pengaturan-paket',
            compact('pakets')
        );
    }

    public function storePaket(Request $request)
    {
        $request->validate([
            'kode_paket' => 'required|string|max:50|unique:pakets,kode_paket',
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|numeric|min:1',
            'harga_bulanan' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status_paket' => 'required|in:aktif,nonaktif',
        ]);

        $paket = new Paket();

        $paket->kode_paket = $request->kode_paket;
        $paket->nama_paket = $request->nama_paket;
        $paket->kecepatan = $request->kecepatan;
        $paket->harga_bulanan = $request->harga_bulanan;
        $paket->deskripsi = $request->deskripsi;
        $paket->status_paket = $request->status_paket;

        $paket->save();

        return back()->with(
            'success',
            'Paket internet berhasil ditambahkan.'
        );
    }

    public function updatePaket(Request $request, $id)
    {
        $paket = Paket::findOrFail($id);

        $request->validate([
            'kode_paket' => 'required|string|max:50|unique:pakets,kode_paket,' . $paket->id,
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|numeric|min:1',
            'harga_bulanan' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status_paket' => 'required|in:aktif,nonaktif',
        ]);

        $paket->kode_paket = $request->kode_paket;
        $paket->nama_paket = $request->nama_paket;
        $paket->kecepatan = $request->kecepatan;
        $paket->harga_bulanan = $request->harga_bulanan;
        $paket->deskripsi = $request->deskripsi;
        $paket->status_paket = $request->status_paket;

        $paket->save();

        return back()->with(
            'success',
            'Paket internet berhasil diperbarui.'
        );
    }
    public function destroyPaket($id)
    {
        $paket = Paket::findOrFail($id);

        if ($paket->pelanggans()->exists()) {
            return back()->with(
                'error',
                'Paket tidak dapat dihapus karena masih digunakan pelanggan.'
            );
        }

        $paket->delete();

        return back()->with(
            'success',
            'Paket internet berhasil dihapus.'
        );
    }

    public function updatePerusahaan(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'telepon_perusahaan' => 'required|string|max:30',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $perusahaan = PengaturanPerusahaan::first();

        if ($perusahaan) {
            $perusahaan->update($validated);
        } else {
            PengaturanPerusahaan::create($validated);
        }

        return redirect('/pengaturan')
            ->with('success', 'Pengaturan perusahaan berhasil disimpan.');
    }

}