<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
    /* Menampilkan dan memfilter data pelanggan */
    public function index(Request $request)
    {
        $query = Pelanggan::with(['user', 'paket']);

        if (in_array($request->query('status'), ['aktif', 'nonaktif', 'isolir'])) {
            $query->where('status', $request->query('status'));
        }

        if (in_array($request->query('tipe'), ['rumah', 'bisnis'])) {
            $query->where('tipe_pelanggan', $request->query('tipe'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_pelanggan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        $pelanggans = $query
            ->orderBy('id_pelanggan', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pelanggan-admin', compact('pelanggans'));
        }

    /* Menampilkan form tambah pelanggan */
    public function create()
    {
        $pakets = Paket::where('status_paket', 'aktif')->get();

        return view('admin.tambah-pelanggan', compact('pakets'));
    }

    /* Menyimpan pelanggan dan membuat akun login */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'paket_id' => 'required|exists:pakets,id',
            'tipe_pelanggan' => 'required|in:rumah,bisnis',
            'tanggal_mulai' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|integer|min:1|max:28',
            'status' => 'required|in:aktif,isolir,nonaktif',
            'catatan' => 'nullable|string',
        ]);

        $credential = null;

        DB::transaction(function () use ($request, &$credential) {
            // Membuat ID pelanggan dan akun
            $lastPelanggan = Pelanggan::latest('id')->first();
            $nomorPelanggan = $lastPelanggan
                ? ((int) str_replace('PLG-', '', $lastPelanggan->id_pelanggan)) + 1
                : 1;

            $idPelanggan = 'PLG-' . str_pad($nomorPelanggan, 4, '0', STR_PAD_LEFT);

            $lastUser = User::latest('id')->first();
            $nomorUser = $lastUser ? $lastUser->id + 1 : 1;
            $idAkun = 'USR-' . str_pad($nomorUser, 4, '0', STR_PAD_LEFT);

            // Membuat username unik
            $username = Str::lower(Str::before($request->email, '@'));
            $baseUsername = $username;
            $angka = 1;

            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $angka++;
            }

            $user = User::create([
                'name' => $request->nama,
                'username' => $username,
                'email' => $request->email,
                'password' => $idAkun,
                'role' => 'pelanggan',
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'status' => $request->status === 'nonaktif' ? 'nonaktif' : 'aktif',
            ]);

            Pelanggan::create([
                'user_id' => $user->id,
                'paket_id' => $request->paket_id,
                'id_pelanggan' => $idPelanggan,
                'nama' => $request->nama,
                'tipe_pelanggan' => $request->tipe_pelanggan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);

            // Membuat folder penyimpanan pembayaran
            $folderPelanggan = 'pelanggan/' . $idPelanggan . '_' . Str::slug($request->nama);
            Storage::disk('public')->makeDirectory($folderPelanggan . '/pembayaran');

            $credential = [
                'id' => $idAkun,
                'id_pelanggan' => $idPelanggan,
                'nama' => $request->nama,
                'username' => $username,
                'password' => $idAkun,
                'role' => 'pelanggan',
            ];
        });

        return redirect('/pelanggan-admin')
            ->with('success', 'Pelanggan dan akun login berhasil dibuat.')
            ->with('akun_pelanggan', $credential);
    }

    /* Menampilkan form edit pelanggan */
    public function edit($id)
    {
        $pelanggan = Pelanggan::with(['user', 'paket'])->findOrFail($id);
        $pakets = Paket::where('status_paket', 'aktif')->get();

        return view('admin.edit-pelanggan', compact('pelanggan', 'pakets'));
    }

    /* Memperbarui data pelanggan dan akun */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pelanggan->user_id,
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'paket_id' => 'required|exists:pakets,id',
            'tipe_pelanggan' => 'required|in:rumah,bisnis',
            'tanggal_mulai' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|integer|min:1|max:28',
            'status' => 'required|in:aktif,isolir,nonaktif',
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $pelanggan) {
            $pelanggan->user->update([
                'name' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'status' => $request->status === 'nonaktif' ? 'nonaktif' : 'aktif',
            ]);

            $pelanggan->update([
                'paket_id' => $request->paket_id,
                'nama' => $request->nama,
                'tipe_pelanggan' => $request->tipe_pelanggan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);
        });

        return redirect('/pelanggan-admin')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /* Menghapus pelanggan, akun, dan folder penyimpanan */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id);

        $namaFolder = Str::slug($pelanggan->user->name ?? $pelanggan->nama);
        $folderPelanggan = 'pelanggan/' . $pelanggan->id_pelanggan . '_' . $namaFolder;

        if (Storage::disk('public')->exists($folderPelanggan)) {
            Storage::disk('public')->deleteDirectory($folderPelanggan);
        }

        $user = $pelanggan->user;
        $pelanggan->delete();

        if ($user) {
            $user->delete();
        }

        return redirect('/pelanggan-admin')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /* Mengekspor data pelanggan ke CSV */
    public function export()
    {
        $pelanggans = Pelanggan::with(['user', 'paket'])->latest()->get();
        $filename = 'data-pelanggan-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($pelanggans) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID Pelanggan', 'Nama', 'Username', 'Email', 'Telepon', 'Alamat',
                'Paket', 'Tipe Pelanggan', 'Tanggal Mulai', 'Tanggal Jatuh Tempo',
                'Status', 'Catatan',
            ], ';');

            foreach ($pelanggans as $pelanggan) {
                fputcsv($handle, [
                    $pelanggan->id_pelanggan,
                    $pelanggan->user->name ?? '',
                    $pelanggan->user->username ?? '',
                    $pelanggan->user->email ?? '',
                    $pelanggan->user->telepon ?? '',
                    $pelanggan->user->alamat ?? '',
                    $pelanggan->paket->nama_paket ?? '',
                    $pelanggan->tipe_pelanggan ?? '',
                    $pelanggan->tanggal_mulai ? \Carbon\Carbon::parse($pelanggan->tanggal_mulai)->format('Y-m-d'): '',
                    $pelanggan->tanggal_jatuh_tempo ?? '',
                    $pelanggan->status ?? '',
                    $pelanggan->catatan ?? '',
                ], ';');
            }

            fclose($handle);
        }, 200, $headers);
    }

    /* Mengimpor data pelanggan dari CSV */
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'file.required' => 'File CSV wajib dipilih.',
            'file.file' => 'File yang dipilih tidak valid.',
            'file.mimes' => 'File harus berformat CSV.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return back()->withErrors([
                'file' => 'File tidak dapat dibaca.',
            ]);
        }

        $header = fgetcsv($handle, 0, ';');

        if (!$header) {
            fclose($handle);

            return back()->withErrors([
                'file' => 'File CSV kosong.',
            ]);
        }

        // Menghapus BOM UTF-8 dari kolom pertama jika ada.
        $header = array_map(function ($item) {
            return trim(preg_replace('/^\xEF\xBB\xBF/', '', $item ?? ''));
        }, $header);

        $berhasil = 0;
        $gagal = 0;
        $daftarGagal = [];
        $nomorBaris = 1;

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $nomorBaris++;

            // Lewati baris yang benar-benar kosong.
            if (count($data) === 1 && trim($data[0] ?? '') === '') {
                continue;
            }

            // Pastikan jumlah kolom sesuai format template.
            if (count($data) !== 12) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Jumlah kolom harus 12 kolom.";

                continue;
            }

            $data = array_map(
                fn ($value) => trim((string) ($value ?? '')),
                $data
            );

            [
                $idPelanggan,
                $nama,
                $username,
                $email,
                $telepon,
                $alamat,
                $namaPaket,
                $tipePelanggan,
                $tanggalMulai,
                $tanggalJatuhTempo,
                $status,
                $catatan
            ] = $data;

            // Validasi kolom wajib.
            $kolomWajib = [
                'Nama' => $nama,
                'Email' => $email,
                'Telepon' => $telepon,
                'Alamat' => $alamat,
                'Paket' => $namaPaket,
                'Tipe Pelanggan' => $tipePelanggan,
                'Tanggal Mulai' => $tanggalMulai,
                'Tanggal Jatuh Tempo' => $tanggalJatuhTempo,
                'Status' => $status,
            ];

            $kolomKosong = [];

            foreach ($kolomWajib as $kolom => $nilai) {
                if ($nilai === '') {
                    $kolomKosong[] = $kolom;
                }
            }

            if (!empty($kolomKosong)) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Kolom wajib kosong: "
                    . implode(', ', $kolomKosong) . '.';

                continue;
            }

            // Validasi format email.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Format email tidak valid ({$email}).";

                continue;
            }

            // Normalisasi tipe pelanggan dan status.
            $tipePelanggan = mb_strtolower(trim($tipePelanggan));
            $status = mb_strtolower(trim($status));

            if (!in_array($tipePelanggan, ['rumah', 'bisnis'], true)) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Tipe pelanggan harus Rumah atau Bisnis.";

                continue;
            }

            if (!in_array($status, ['aktif', 'isolir', 'nonaktif'], true)) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Status harus Aktif, Isolir, atau Nonaktif.";

                continue;
            }

            // Normalisasi tanggal mulai.
            try {
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $tanggalMulai)) {
                    $tanggalMulai = \Carbon\Carbon::createFromFormat(
                        '!d/m/Y',
                        $tanggalMulai
                    )->format('Y-m-d');
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalMulai)) {
                    $tanggalMulai = \Carbon\Carbon::createFromFormat(
                        '!Y-m-d',
                        $tanggalMulai
                    )->format('Y-m-d');
                } else {
                    throw new \InvalidArgumentException(
                        'Format tanggal tidak dikenal.'
                    );
                }
            } catch (\Throwable $e) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Tanggal Mulai tidak valid. "
                    . 'Gunakan format DD/MM/YYYY atau YYYY-MM-DD.';

                continue;
            }

            // Validasi tanggal jatuh tempo (tanggal 1 sampai 28).
            if (
                !ctype_digit($tanggalJatuhTempo) ||
                (int) $tanggalJatuhTempo < 1 ||
                (int) $tanggalJatuhTempo > 28
            ) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Tanggal Jatuh Tempo harus berupa "
                    . 'angka 1 sampai 28.';

                continue;
            }

            $tanggalJatuhTempo = (int) $tanggalJatuhTempo;

            // Periksa email duplikat.
            if (User::where('email', $email)->exists()) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Email {$email} sudah terdaftar.";

                continue;
            }

            // Cari paket tanpa membedakan huruf besar/kecil.
            $paket = Paket::whereRaw(
                'LOWER(TRIM(nama_paket)) = ?',
                [mb_strtolower(trim($namaPaket))]
            )->first();

            if (!$paket) {
                $gagal++;
                $daftarGagal[] =
                    "Baris {$nomorBaris}: Paket '{$namaPaket}' tidak ditemukan.";

                continue;
            }

            try {
                DB::transaction(function () use (
                    $nama,
                    $username,
                    $email,
                    $telepon,
                    $alamat,
                    $paket,
                    $tipePelanggan,
                    $tanggalMulai,
                    $tanggalJatuhTempo,
                    $status,
                    $catatan
                ) {
                    // Membuat username otomatis jika kosong.
                    if ($username === '') {
                        $username = Str::lower(Str::before($email, '@'));
                    }

                    // Memastikan username unik.
                    $baseUsername = $username;
                    $angka = 1;

                    while (User::where('username', $username)->exists()) {
                        $username = $baseUsername . $angka++;
                    }

                    // Membuat ID akun.
                    $lastUser = User::latest('id')->first();
                    $nomorUser = $lastUser ? $lastUser->id + 1 : 1;
                    $idAkun = 'USR-' . str_pad(
                        $nomorUser,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                    // Membuat ID pelanggan.
                    $lastPelanggan = Pelanggan::latest('id')->first();

                    $nomorPelanggan = $lastPelanggan
                        ? ((int) str_replace(
                            'PLG-',
                            '',
                            $lastPelanggan->id_pelanggan
                        )) + 1
                        : 1;

                    $idPelanggan = 'PLG-' . str_pad(
                        $nomorPelanggan,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                    $user = User::create([
                        'name' => $nama,
                        'username' => $username,
                        'email' => $email,
                        'password' => $idAkun,
                        'role' => 'pelanggan',
                        'telepon' => $telepon,
                        'alamat' => $alamat,
                        'status' => $status === 'nonaktif'
                            ? 'nonaktif'
                            : 'aktif',
                    ]);

                    Pelanggan::create([
                        'user_id' => $user->id,
                        'paket_id' => $paket->id,
                        'id_pelanggan' => $idPelanggan,
                        'nama' => $nama,
                        'tipe_pelanggan' => $tipePelanggan,
                        'tanggal_mulai' => $tanggalMulai,
                        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                        'status' => $status,
                        'catatan' => $catatan,
                    ]);

                    // Membuat folder pembayaran pelanggan.
                    $folderPelanggan = 'pelanggan/'
                        . $idPelanggan . '_' . Str::slug($nama);

                    Storage::disk('public')->makeDirectory(
                        $folderPelanggan . '/pembayaran'
                    );
                });

                $berhasil++;
            } catch (\Throwable $e) {
                $gagal++;

                // Catat pesan yang aman untuk membantu pemeriksaan.
                Log::error('Import pelanggan gagal', [
                    'baris' => $nomorBaris,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);

                $daftarGagal[] =
                    "Baris {$nomorBaris}: Gagal menyimpan data. "
                    . 'Periksa log aplikasi untuk detailnya.';
            }
        }

        fclose($handle);

        return redirect('/pelanggan-admin')->with([
            'success' => "Import selesai. {$berhasil} pelanggan berhasil "
                . "ditambahkan, {$gagal} data gagal diimpor.",
            'import_errors' => $daftarGagal,
        ]);
    }
    
    public function downloadTemplate()
    {
        $namaFile = 'template-import-pelanggan.csv';

        $header = [
            'ID Pelanggan',
            'Nama',
            'Username',
            'Email',
            'Telepon',
            'Alamat',
            'Paket',
            'Tipe Pelanggan',
            'Tanggal Mulai',
            'Tanggal Jatuh Tempo',
            'Status',
            'Catatan',
        ];

        return response()->streamDownload(function () use ($header) {
            $output = fopen('php://output', 'w');

            // BOM agar karakter CSV terbaca baik di Excel.
            fwrite($output, "\xEF\xBB\xBF");

            // Header menggunakan pemisah titik koma.
            fputcsv($output, $header, ';');

            fclose($output);
        }, $namaFile, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    
    public function hapusMassal(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_ids' => ['required', 'array', 'min:1'],
            'pelanggan_ids.*' => ['required', 'integer', 'distinct', 'exists:pelanggans,id'],
        ], [
            'pelanggan_ids.required' => 'Pilih minimal satu pelanggan.',
            'pelanggan_ids.min' => 'Pilih minimal satu pelanggan.',
        ]);

        $jumlah = 0;

        DB::transaction(function () use ($validated, &$jumlah) {
            $pelanggans = Pelanggan::whereIn(
                'id',
                $validated['pelanggan_ids']
            )->get();

            foreach ($pelanggans as $pelanggan) {
                $user = User::find($pelanggan->user_id);

                $pelanggan->delete();

                if ($user) {
                    $user->delete();
                }

                $jumlah++;
            }
        });

        return redirect('/pelanggan-admin')->with(
            'success',
            "{$jumlah} pelanggan berhasil dihapus."
        );
    }



}
