<?php
namespace App\Http\Controllers;

use App\Models\PenugasanTeknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasTeknisiController extends Controller
{
    public function index(Request $request)
    {
        $teknisi = Auth::user()->teknisi;

        $query = PenugasanTeknisi::with([
            'tiketGangguan.pelanggan.user',
            'tiketGangguan.laporanGangguan'
        ])->where('teknisi_id', $teknisi->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('tiketGangguan', function ($q) use ($search) {
                    $q->where('id_tiket', 'like', '%'.$search.'%');
                })->orWhereHas('tiketGangguan.pelanggan.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                })->orWhereHas('tiketGangguan.laporanGangguan', function ($q) use ($search) {
                    $q->where('jenis_gangguan', 'like', '%'.$search.'%')
                      ->orWhere('deskripsi', 'like', '%'.$search.'%');
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tugas = $query->latest('tanggal_penugasan')->paginate(10)->withQueryString();

        $totalTugas = PenugasanTeknisi::where('teknisi_id', $teknisi->id)->count();
        $belumDikerjakan = PenugasanTeknisi::where('teknisi_id', $teknisi->id)
            ->whereIn('status', ['ditugaskan', 'menunggu', 'belum_dikerjakan'])
            ->count();
        $sedangDitangani = PenugasanTeknisi::where('teknisi_id', $teknisi->id)
            ->whereIn('status', ['diproses', 'dalam_proses'])
            ->count();
        $selesai = PenugasanTeknisi::where('teknisi_id', $teknisi->id)
            ->where('status', 'selesai')
            ->count();

        return view('teknisi.tugas-teknisi', compact(
            'teknisi',
            'tugas',
            'totalTugas',
            'belumDikerjakan',
            'sedangDitangani',
            'selesai'
        ));
    }
}