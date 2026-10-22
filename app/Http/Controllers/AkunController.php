<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->whereIn('role', ['admin', 'teknisi']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role && in_array($request->role, ['admin', 'teknisi'])) {
            $query->where('role', $request->role);
        }

        if ($request->status && in_array($request->status, ['aktif', 'nonaktif'])) {
            $query->where('status', $request->status);
        }

        $akuns = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalAkun = User::whereIn('role', ['admin', 'teknisi'])->count();

        $akunAktif = User::whereIn('role', ['admin', 'teknisi'])
            ->where('status', 'aktif')
            ->count();

        $totalAdmin = User::where('role', 'admin')->count();

        $totalTeknisi = User::where('role', 'teknisi')->count();

        return view('admin.akun-admin', compact(
            'akuns',
            'totalAkun',
            'akunAktif',
            'totalAdmin',
            'totalTeknisi'
        ));
    }

    public function create()
    {
        $lastUser = User::latest('id')->first();

        $nomor = $lastUser
            ? $lastUser->id + 1
            : 1;

        $idAkun = 'USR-' . str_pad(
            $nomor,
            4,
            '0',
            STR_PAD_LEFT
        );

        return view(
            'admin.tambah-akun',
            compact('idAkun')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                'in:admin,teknisi',
            ],

            'telepon' => 'nullable|string|max:20',

            'alamat' => 'nullable|string',

            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],
        ]);

        $lastUser = User::latest('id')->first();

        $nomor = $lastUser
            ? $lastUser->id + 1
            : 1;

        $idAkun = 'USR-' . str_pad(
            $nomor,
            4,
            '0',
            STR_PAD_LEFT
        );

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $idAkun,
            'role' => $request->role,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        session()->flash('akun_baru', [
            'id' => $idAkun,
            'nama' => $request->name,
            'username' => $request->username,
            'password' => $idAkun,
            'role' => $request->role,
        ]);

        return redirect('/akun-admin')
            ->with(
                'success',
                'Akun ' . $idAkun . ' berhasil dibuat.'
            );
    }

    public function edit($id)
    {
        $akun = User::whereIn(
            'role',
            ['admin', 'teknisi']
        )->findOrFail($id);

        return view(
            'admin.edit-akun',
            compact('akun')
        );
    }

    public function update(Request $request, $id)
    {
        $akun = User::whereIn(
            'role',
            ['admin', 'teknisi']
        )->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',

            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'users',
                    'username'
                )->ignore($akun->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore($akun->id),
            ],

            'password' => 'nullable|string|min:8',

            'role' => [
                'required',
                'in:admin,teknisi',
            ],

            'telepon' => 'nullable|string|max:20',

            'alamat' => 'nullable|string',

            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $akun->update($data);

        return redirect('/akun-admin')
            ->with(
                'success',
                'Akun berhasil diperbarui.'
            );
    }

    public function resetPassword($id)
    {
        $akun = User::whereIn(
            'role',
            ['admin', 'teknisi']
        )->findOrFail($id);

        $passwordBaru = 'USR-' . str_pad(
            $akun->id,
            4,
            '0',
            STR_PAD_LEFT
        );

        $akun->update([
            'password' => $passwordBaru,
        ]);

        session()->flash('password_reset', [
            'nama' => $akun->name,
            'username' => $akun->username,
            'password' => $passwordBaru,
        ]);

        return redirect('/akun-admin')
            ->with(
                'success',
                'Password akun berhasil direset.'
            );
    }

    public function destroy($id)
    {
        $akun = User::whereIn(
            'role',
            ['admin', 'teknisi']
        )->findOrFail($id);

        $akun->delete();

        return redirect('/akun-admin')
            ->with(
                'success',
                'Akun berhasil dihapus.'
            );
    }
}