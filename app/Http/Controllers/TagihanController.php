<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TagihanController extends Controller
{
    /* DAFTAR TAGIHAN ADMIN */
    public function index(Request $request)
    {
        $query = Tagihan::with(['pelanggan.user', 'paket']);

        if ($request->query('status') === 'belum_bayar') {
            $query->where('status', 'belum_bayar');
        }

        $tagihan = $query
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tagihan-admin', compact('tagihan'));
    }

    /* FORM TAMBAH TAGIHAN MANUAL */
    public function create(Request $request)
    {
        $pelanggans = Pelanggan::with(['user', 'paket'])
            ->where('status', 'aktif')
            ->get();

        $pelangganTerpilih = $request->query('pelanggan');

        return view('admin.tambah-tagihan', compact(
            'pelanggans',
            'pelangganTerpilih'
        ));
    }

    /* SIMPAN TAGIHAN MANUAL */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal_terbit' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_terbit',
            'status' => 'required|in:belum_bayar,lunas',
            'catatan' => 'nullable|string',
        ]);

        $pelanggan = Pelanggan::with('paket')
            ->findOrFail($request->pelanggan_id);

        if (!$pelanggan->paket) {
            return back()->withInput()->withErrors([
                'pelanggan_id' => 'Pelanggan belum memiliki paket.',
            ]);
        }

        $last = Tagihan::latest('id')->first();
        $nomor = $last ? $last->id + 1 : 1;

        $tagihan = Tagihan::create([
            'kode_tagihan' => 'TAG-' . str_pad($nomor, 4, '0', STR_PAD_LEFT),
            'pelanggan_id' => $pelanggan->id,
            'paket_id' => $pelanggan->paket_id,
            'jumlah' => $pelanggan->paket->harga_bulanan,
            'tanggal_terbit' => $request->tanggal_terbit,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        if ($pelanggan->user) {
            app(PushNotificationService::class)->send(
                $pelanggan->user,
                'Tagihan Baru',
                'Tagihan ' . $tagihan->kode_tagihan . ' telah dibuat. Silakan cek tagihan Anda.',
                'tagihan',
                url('/tagihan-pelanggan'),
                'tagihan',
                $tagihan->id
            );
        }

        return redirect('/tagihan-admin')
            ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    /* GENERATE TAGIHAN BULANAN OTOMATIS */
    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2100',
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;
        $tanggalTerbit = Carbon::create($tahun, $bulan, 1);

        $pelanggans = Pelanggan::with(['user', 'paket'])
            ->where('status', 'aktif')
            ->get();

        $berhasil = 0;
        $dilewati = 0;
        $tanpaPaket = 0;
        $nomorTagihan = (Tagihan::max('id') ?? 0) + 1;

        foreach ($pelanggans as $pelanggan) {
            if (!$pelanggan->paket) {
                $tanpaPaket++;
                continue;
            }

            $sudahAda = Tagihan::where('pelanggan_id', $pelanggan->id)
                ->whereYear('tanggal_terbit', $tahun)
                ->whereMonth('tanggal_terbit', $bulan)
                ->exists();

            if ($sudahAda) {
                $dilewati++;
                continue;
            }

            $hariJatuhTempo = (int) $pelanggan->tanggal_jatuh_tempo;
            $hariJatuhTempo = max(1, min(
                $hariJatuhTempo,
                $tanggalTerbit->daysInMonth
            ));

            $tanggalJatuhTempo = Carbon::create(
                $tahun,
                $bulan,
                $hariJatuhTempo
            );

            $kodeTagihan = 'TAG-' . str_pad(
                $nomorTagihan,
                4,
                '0',
                STR_PAD_LEFT
            );

            $tagihan = Tagihan::create([
                'kode_tagihan' => $kodeTagihan,
                'pelanggan_id' => $pelanggan->id,
                'paket_id' => $pelanggan->paket_id,
                'jumlah' => $pelanggan->paket->harga_bulanan,
                'tanggal_terbit' => $tanggalTerbit->format('Y-m-d'),
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                'status' => 'belum_bayar',
                'catatan' => 'Tagihan periode ' . $tanggalTerbit->translatedFormat('F Y'),
            ]);

            $nomorTagihan++;
            $berhasil++;

            if ($pelanggan->user) {
                app(PushNotificationService::class)->send(
                    $pelanggan->user,
                    'Tagihan Baru',
                    'Tagihan ' . $tagihan->kode_tagihan .
                        ' untuk periode ' . $tanggalTerbit->translatedFormat('F Y') .
                        ' telah dibuat. Silakan cek tagihan Anda.',
                    'tagihan',
                    url('/tagihan-pelanggan'),
                    'tagihan',
                    $tagihan->id
                );
            }
        }

        $pesan = $berhasil . ' tagihan berhasil dibuat untuk periode ' .
            $tanggalTerbit->translatedFormat('F Y') . '.';

        if ($dilewati > 0) {
            $pesan .= ' ' . $dilewati .
                ' pelanggan dilewati karena sudah memiliki tagihan pada periode tersebut.';
        }

        if ($tanpaPaket > 0) {
            $pesan .= ' ' . $tanpaPaket .
                ' pelanggan dilewati karena belum memiliki paket.';
        }

        return redirect('/tagihan-admin')->with('success', $pesan);
    }

    /* DETAIL TAGIHAN */
    public function show($id)
    {
        $tagihan = Tagihan::with([
            'pelanggan.user',
            'paket',
            'pembayaran',
        ])->findOrFail($id);

        return view('admin.detail-tagihan', compact('tagihan'));
    }

    /* DAFTAR TAGIHAN PELANGGAN */
    public function pelanggan()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        $tagihan = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->where('status', '!=', 'lunas')
            ->latest()
            ->get();

        $pembayaran = Pembayaran::whereIn(
            'tagihan_id',
            $tagihan->pluck('id')
        )->get()->keyBy('tagihan_id');

        return view('pelanggan.tagihan-pelanggan', compact(
            'tagihan',
            'pembayaran'
        ));
    }
}
