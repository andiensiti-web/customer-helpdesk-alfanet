<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganTeknisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with([
            'user',
            'paket'
        ]);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_pelanggan', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', '%' . $search . '%')
                            ->orWhere('telepon', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }
        $pelanggans = $query
            ->orderBy('id_pelanggan', 'asc')
            ->paginate(10)
            ->withQueryString();
        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::where(
            'status',
            'aktif'
        )->count();
        $pelangganIsolir = Pelanggan::where(
            'status',
            'isolir'
        )->count();
        $pelangganNonaktif = Pelanggan::where(
            'status',
            'nonaktif'
        )->count();
        return view(
            'teknisi.pelanggan-teknisi',
            compact(
                'pelanggans',
                'totalPelanggan',
                'pelangganAktif',
                'pelangganIsolir',
                'pelangganNonaktif'
            )
        );
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with([
            'user',
            'paket',
            'laporanGangguan'
        ])->findOrFail($id);

        return view(
            'teknisi.detail-pelanggan',
            compact('pelanggan')
        );
    }
}