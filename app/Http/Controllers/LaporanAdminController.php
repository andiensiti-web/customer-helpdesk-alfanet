<?php

namespace App\Http\Controllers;

use App\Models\LaporanTeknisi;
use App\Models\LaporanGangguan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class LaporanAdminController extends Controller
{
 public function index(Request $request){
    $queryLaporan = LaporanTeknisi::with([
        'pelanggan.user',
        'teknisi',
        'penugasan.tiketGangguan',
        'penugasan.perbaikan'
    ]);
    if ($request->filled('bulan')) {
        $queryLaporan->whereMonth(
            'tanggal_laporan',
            $request->bulan
        );
    }
    if ($request->filled('jenis')) {
        if ($request->jenis == 'perbaikan') {
            $queryLaporan->whereNotNull(
                'hasil_perbaikan'
            );
        }
    }
    if ($request->filled('status')) {
        $queryLaporan->where(
            'status',
            $request->status
        );
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $queryLaporan->where(function($q) use ($search){
            $q->where(
                'id_laporan',
                'like',
                '%'.$search.'%'
            )
            ->orWhereHas('pelanggan.user', function($q) use ($search){
                $q->where(
                    'name',
                    'like',
                    '%'.$search.'%'
                );
            })
            ->orWhereHas('teknisi', function($q) use ($search){
                $q->where(
                    'nama',
                    'like',
                    '%'.$search.'%'
                );
            });
        });
    }
    $laporanTeknisi = $queryLaporan
        ->get()
        ->map(function($item){
            $item->jenis_data = 'laporan';
            return $item;
        });
    if ($request->jenis == 'pembayaran') {
        $laporanTeknisi = collect();
    }
    $queryPembayaran = Pembayaran::with([
        'pelanggan.user'
    ]);
    if ($request->jenis == 'perbaikan') {
        $queryPembayaran->whereRaw('1 = 0');
    }
    if ($request->filled('bulan')) {
        $queryPembayaran->whereMonth(
            'tanggal_pembayaran',
            $request->bulan
        );
    }
    if ($request->filled('status')) {
        $queryPembayaran->where(
            'status',
            $request->status
        );
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $queryPembayaran->where(function($q) use ($search){
            $q->where(
                'kode_pembayaran',
                'like',
                '%'.$search.'%'
            )
            ->orWhereHas('pelanggan.user', function($q) use ($search){
                $q->where(
                    'name',
                    'like',
                    '%'.$search.'%'
                );
            });
        });
    }
    $pembayaran = $queryPembayaran
        ->latest()
        ->get()
        ->map(function($item){
            $item->jenis_data = 'pembayaran';
            return $item;
        });
    $laporans = $laporanTeknisi
        ->concat($pembayaran)
        ->sortByDesc('created_at');
    $totalPelanggan = Pelanggan::count();
    $totalGangguan = LaporanGangguan::count();
    $totalPembayaran = Pembayaran::where(
        'status',
        'terverifikasi'
    )->count();
    $pendapatan = Pembayaran::where(
        'status',
        'terverifikasi'
    )->sum('jumlah');
    $jumlahMenunggu = LaporanTeknisi::where(
        'status',
        'menunggu_approval'
    )->count();

    return view('admin.laporan-admin', compact(
        'laporans',
        'totalPelanggan',
        'totalGangguan',
        'totalPembayaran',
        'pendapatan',
        'jumlahMenunggu'
    ));
}

    public function menunggu(Request $request)
    {
    $query = LaporanTeknisi::with([
        'pelanggan.user',
        'teknisi',
        'penugasan.tiketGangguan',
        'penugasan.perbaikan'
    ])
    ->where(
        'status',
        'menunggu_approval'
    );
    if ($request->filled('bulan')) {
        $query->whereMonth(
            'tanggal_laporan',
            $request->bulan
        );
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search){
            $q->where(
                'id_laporan',
                'like',
                '%'.$search.'%'
            )
            ->orWhereHas(
                'pelanggan.user',
                function($q) use ($search){
                    $q->where(
                        'name',
                        'like',
                        '%'.$search.'%'
                    );
                }
            )
            ->orWhereHas(
                'teknisi',
                function($q) use ($search){
                    $q->where(
                        'nama',
                        'like',
                        '%'.$search.'%'
                    );
                }
            );
        });
    }
    $laporans = $query
    ->latest('tanggal_laporan')
    ->paginate(10)
    ->withQueryString();
    $totalPelanggan = Pelanggan::count();
    $totalGangguan = LaporanGangguan::count();
    $totalPembayaran = Pembayaran::where(
        'status',
        'terverifikasi'
    )->count();
    $pendapatan = Pembayaran::where(
        'status',
        'terverifikasi'
    )->sum('jumlah');
    $jumlahMenunggu = LaporanTeknisi::where(
        'status',
        'menunggu_approval'
    )->count();
        return view(
            'admin.laporan-menunggu',
            compact(
                'laporans',
                'totalPelanggan',
                'totalGangguan',
                'totalPembayaran',
                'pendapatan',
                'jumlahMenunggu'
            )
        );
    }

    public function disetujui(Request $request)
    {
    $query = LaporanTeknisi::with([
        'pelanggan.user',
        'teknisi',
        'penugasan.tiketGangguan',
        'penugasan.perbaikan'
    ])
    ->where(
        'status',
        'disetujui'
    );
    if ($request->filled('bulan')) {
        $query->whereMonth(
            'tanggal_laporan',
            $request->bulan
        );
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search){
            $q->where(
                'id_laporan',
                'like',
                '%'.$search.'%'
            )
            ->orWhereHas(
                'pelanggan.user',
                function($q) use ($search){
                    $q->where(
                        'name',
                        'like',
                        '%'.$search.'%'
                    );
                }
            );
        });
    }
    $laporans = $query
    ->latest('tanggal_laporan')
    ->paginate(10)
    ->withQueryString();
    $totalPelanggan = Pelanggan::count();
    $totalGangguan = LaporanGangguan::count();
    $totalPembayaran = Pembayaran::where(
        'status',
        'terverifikasi'
    )->count();
    $pendapatan = Pembayaran::where(
        'status',
        'terverifikasi'
    )->sum('jumlah');
    $jumlahMenunggu = LaporanTeknisi::where(
        'status',
        'menunggu_approval'
    )->count();
    return view(
    'admin.laporan-disetujui',
        compact(
            'laporans',
            'totalPelanggan',
            'totalGangguan',
            'totalPembayaran',
            'pendapatan',
            'jumlahMenunggu'
        )
    );
    }

    public function ditolak(Request $request)
    {
    $query = LaporanTeknisi::with([
        'pelanggan.user',
        'teknisi',
        'penugasan.tiketGangguan',
        'penugasan.perbaikan'
    ])
    ->where(
        'status',
        'ditolak'
    );
    if ($request->filled('bulan')) {
        $query->whereMonth(
            'tanggal_laporan',
            $request->bulan
        );
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search){
            $q->where(
                'id_laporan',
                'like',
                '%'.$search.'%'
            )
            ->orWhereHas(
                'pelanggan.user',
                function($q) use ($search){
                    $q->where(
                        'name',
                        'like',
                        '%'.$search.'%'
                    );
                }
            );
        });
    }
    $laporans = $query
    ->latest('tanggal_laporan')
    ->paginate(10)
    ->withQueryString();
    $totalPelanggan = Pelanggan::count();
    $totalGangguan = LaporanGangguan::count();
    $totalPembayaran = Pembayaran::where(
        'status',
        'terverifikasi'
    )->count();
    $pendapatan = Pembayaran::where(
        'status',
        'terverifikasi'
    )->sum('jumlah');
    $jumlahMenunggu = LaporanTeknisi::where(
        'status',
        'menunggu_approval'
    )->count();
    return view(
        'admin.laporan-ditolak',
        compact(
            'laporans',
            'totalPelanggan',
            'totalGangguan',
            'totalPembayaran',
            'pendapatan',
            'jumlahMenunggu'
        )
    );
    }

    public function show($id)
    {
        $laporan = LaporanTeknisi::with([
            'pelanggan.user',
            'teknisi',
            'penugasan.tiketGangguan',
            'penugasan.perbaikan'
        ])->findOrFail($id);

        return view('admin.laporan-detail', compact('laporan'));
    }

    public function approve($id)
    {
    $laporan = LaporanTeknisi::with('teknisi.user')
        ->findOrFail($id);

    $laporan->update([
        'status' => 'disetujui',
    ]);

    $teknisiUser = $laporan->teknisi?->user;

    if ($teknisiUser) {

        $setting = $teknisiUser->notificationSetting;

        if (
            $setting &&
            $setting->laporan_disetujui
        ) {

            app(PushNotificationService::class)->send(
                $teknisiUser,
                'Laporan Disetujui',
                'Laporan '.$laporan->id_laporan.' telah disetujui Admin.',
                'laporan_teknisi',
                url('/laporan-teknisi'),
                'laporan_teknisi',
                $laporan->id
            );

        }
    }

    return redirect('/laporan-detail/'.$id)
        ->with('success', 'Laporan berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $laporan = LaporanTeknisi::with('teknisi.user')
            ->findOrFail($id);

        $laporan->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan,
        ]);

        $teknisiUser = $laporan->teknisi?->user;

        if ($teknisiUser) {

    $setting = $teknisiUser->notificationSetting;

    if (
        $setting &&
        $setting->laporan_disetujui
    ) {

        app(PushNotificationService::class)->send(
            $teknisiUser,
            'Laporan Perlu Diperbaiki',
            'Laporan '.$laporan->id_laporan.' ditolak Admin. Silakan periksa catatan.',
            'laporan_teknisi',
            url('/buat-laporan-teknisi/'.$laporan->penugasan_teknisi_id),
            'laporan_teknisi',
            $laporan->id
        );

    }

}

        return redirect('/laporan-detail/'.$id)
            ->with('success', 'Laporan berhasil ditolak.');
    }


    public function keuangan(Request $request)
    {
        $query = Pembayaran::with(['pelanggan.user'])
            ->where('status', 'terverifikasi');

        // Filter berdasarkan periode dari card dashboard
        if ($request->periode === 'bulan_kemarin') {
            $tanggalAwal = now()->subMonthNoOverflow()->startOfMonth();
            $tanggalAkhir = now()->subMonthNoOverflow()->endOfMonth();

            $query->whereBetween('tanggal_pembayaran', [
                $tanggalAwal,
                $tanggalAkhir
            ]);
        } elseif ($request->periode === 'bulan_ini') {
            $query->whereBetween('tanggal_pembayaran', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        } elseif ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pembayaran', $request->bulan);
        }

        // Filter pencarian tetap berfungsi
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_pembayaran', 'like', '%' . $search . '%')
                    ->orWhereHas('pelanggan.user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $pembayaran = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalTagihan = Tagihan::sum('jumlah');

        // Total mengikuti periode yang sedang dipilih
        $totalPembayaran = (clone $query)->count();
        $pendapatan = (clone $query)->sum('jumlah');

        return view('admin.laporan-keuangan', compact(
            'pembayaran',
            'totalTagihan',
            'totalPembayaran',
            'pendapatan'
        ));
    }


    public function exportPdf(Request $request)
    {
    $laporanTeknisi = LaporanTeknisi::with([
        'pelanggan.user',
        'teknisi',
        'penugasan.tiketGangguan',
        'penugasan.perbaikan'
    ]);
    if ($request->filled('bulan')) {
        $laporanTeknisi->whereMonth(
            'tanggal_laporan',
            $request->bulan
        );
    }
    $laporanTeknisi = $laporanTeknisi
        ->latest('tanggal_laporan')
        ->get()
        ->map(function($item){
            $item->jenis_data = 'laporan';
            return $item;
        });

    $pembayaran = Pembayaran::with([
        'pelanggan.user'
    ]);

    if ($request->filled('bulan')) {
        $pembayaran->whereMonth(
            'tanggal_pembayaran',
            $request->bulan
        );
    }
    $pembayaran = $pembayaran
        ->latest()
        ->get()
        ->map(function($item){
            $item->jenis_data = 'pembayaran';
            return $item;
        });
    $laporans = $laporanTeknisi
        ->concat($pembayaran)
        ->sortByDesc('created_at');
    return view(
        'admin.laporan-pdf',
        compact('laporans')
    );
}

    public function print($id)
    {
        $laporan = LaporanTeknisi::with([
            'pelanggan.user',
            'teknisi',
            'penugasan.tiketGangguan',
            'penugasan.perbaikan'
        ])->findOrFail($id);

        return view('admin.laporan-pdf-satuan', compact('laporan'));
    }
}