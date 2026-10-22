<?php

namespace App\Http\Controllers;

use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Teknisi::with('user');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'id_teknisi',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'nama',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'email',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'telepon',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'keahlian',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where(
                        'username',
                        'like',
                        '%' . $search . '%'
                    );
                });
            });
        }

        // FILTER STATUS
        if (
            $request->filled('status') &&
            $request->status !== 'semua'
        ) {
            $query->where(
                'status',
                $request->status
            );
        }

        // URUTKAN ID TEKNISI
        $teknisis = $query
            ->orderBy('id_teknisi', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.teknisi-admin',
            compact('teknisis')
        );
    }

    public function create()
    {
        return view('admin.tambah-teknisi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telepon' => 'required|string|max:20',
            'keahlian' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,isolir,nonaktif',
            'alamat' => 'required|string',

            // FOTO
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $credential = null;

        DB::transaction(function () use (
            $request,
            &$credential
        ) {

            $lastTeknisi = Teknisi::orderBy(
                'id',
                'desc'
            )->first();

            $nomorTeknisi = $lastTeknisi
                ? (
                    (int) str_replace(
                        'TKN-',
                        '',
                        $lastTeknisi->id_teknisi
                    )
                ) + 1
                : 1;

            $idTeknisi = 'TKN-' . str_pad(
                $nomorTeknisi,
                4,
                '0',
                STR_PAD_LEFT
            );
         
            $lastUser = User::orderBy(
                'id',
                'desc'
            )->first();

            $nomorUser = $lastUser
                ? $lastUser->id + 1
                : 1;

            $idAkun = 'USR-' . str_pad(
                $nomorUser,
                4,
                '0',
                STR_PAD_LEFT
            );

            $username = Str::lower(
                Str::before(
                    $request->email,
                    '@'
                )
            );

            $baseUsername = $username;
            $angka = 1;

            while (
                User::where(
                    'username',
                    $username
                )->exists()
            ) {
                $username = $baseUsername . $angka;
                $angka++;
            }

            $statusUser = $request->status === 'nonaktif'
                ? 'nonaktif'
                : 'aktif';

            $user = User::create([
                'name' => $request->nama,
                'username' => $username,
                'email' => $request->email,
                'password' => $idAkun,
                'role' => 'teknisi',
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'status' => $statusUser,
            ]);

          
            $folderTeknisi = 'teknisi/' .
                $idTeknisi .
                '/foto';

            Storage::disk('public')->makeDirectory(
                $folderTeknisi
            );
            
            $fotoPath = null;

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $namaFoto = now()->format('Ymd-His') . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs($folderTeknisi, $namaFoto, 'public');
            }

                Teknisi::create([
                'user_id' => $user->id,
                'id_teknisi' => $idTeknisi,
                'nama' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'keahlian' => $request->keahlian,
                'status' => $request->status,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
            ]);

            $credential = [
                'id' => $idAkun,
                'id_teknisi' => $idTeknisi,
                'nama' => $request->nama,
                'username' => $username,
                'password' => $idAkun,
                'role' => 'teknisi',
            ];
        });

        return redirect('/teknisi-admin')
            ->with(
                'success',
                'Teknisi dan akun login berhasil dibuat.'
            )
            ->with(
                'akun_teknisi',
                $credential
            );
    }

    public function edit($id)
    {
        $teknisi = Teknisi::with('user')
            ->findOrFail($id);

        return view(
            'admin.edit-teknisi',
            compact('teknisi')
        );
    }

    public function update(
        Request $request,
        $id
    ) {
        $teknisi = Teknisi::with('user')
            ->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' .
                $teknisi->user_id,

            'telepon' => 'required|string|max:20',

            'keahlian' => 'nullable|string|max:255',

            'status' => 'required|in:aktif,isolir,nonaktif',

            'alamat' => 'required|string',

            // FOTO
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use (
            $request,
            $teknisi
        ) {

            $statusUser = $request->status === 'nonaktif'
                ? 'nonaktif'
                : 'aktif';

            if ($teknisi->user) {

                $teknisi->user->update([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'telepon' => $request->telepon,
                    'alamat' => $request->alamat,
                    'status' => $statusUser,
                ]);
            }

            $folderTeknisi = 'teknisi/' .
                $teknisi->id_teknisi .
                '/foto';

            Storage::disk('public')->makeDirectory(
                $folderTeknisi
            );

            $fotoPath = $teknisi->foto;

            if ($request->hasFile('foto')) {

                if ($teknisi->foto) {

                    Storage::disk('public')->delete(
                        $teknisi->foto
                    );
                }

                $foto = $request->file('foto');

                $namaFoto = 'foto-profil.' .
                    $foto->getClientOriginalExtension();

                $fotoPath = $foto->storeAs(
                    $folderTeknisi,
                    $namaFoto,
                    'public'
                );
            }

            $teknisi->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'keahlian' => $request->keahlian,
                'status' => $request->status,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
            ]);
        });

        return redirect('/teknisi-admin')
            ->with(
                'success',
                'Data teknisi berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        $teknisi = Teknisi::with('user')
            ->findOrFail($id);

        DB::transaction(function () use ($teknisi) {

            $user = $teknisi->user;

            $folderTeknisi = 'teknisi/' .
                $teknisi->id_teknisi;

            if (
                Storage::disk('public')
                    ->exists($folderTeknisi)
            ) {
                Storage::disk('public')->deleteDirectory(
                    $folderTeknisi
                );
            }

            $teknisi->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect('/teknisi-admin')
            ->with(
                'success',
                'Teknisi berhasil dihapus.'
            );
    }
}