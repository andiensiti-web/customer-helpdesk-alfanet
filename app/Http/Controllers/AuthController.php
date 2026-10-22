<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengaturanPerusahaan;
class AuthController extends Controller
{
    // =========================
    // LOGIN ADMIN & TEKNISI
    // =========================

    public function showLogin()
    {
        $pengaturan = PengaturanPerusahaan::first();

        return view('auth.login', compact('pengaturan'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'status' => 'aktif',
        ], $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'username' => 'Username atau password salah, atau akun tidak aktif.',
                ])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect('/dashboard');
        }

        if ($user->role === 'teknisi') {
            return redirect('/dashboard-teknisi');
        }

        Auth::logout();

        return back()
            ->withErrors([
                'username' => 'Akun ini tidak dapat digunakan untuk login melalui halaman ini.',
            ])
            ->onlyInput('username');
    }

    // =========================
    // LOGIN PELANGGAN
    // =========================

    public function showLoginPelanggan()
    {
        $pengaturan = PengaturanPerusahaan::first();

        return view('auth.login-pelanggan', compact('pengaturan'));
    }

    public function loginPelanggan(Request $request)
    {
        $request->validate([
            'telepon' => 'required|string',
        ]);

        $nomor = preg_replace(
            '/[^0-9]/',
            '',
            $request->telepon
        );

        $user = User::where('telepon', $nomor)
            ->where('role', 'pelanggan')
            ->where('status', 'aktif')
            ->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'telepon' => 'Nomor WhatsApp tidak ditemukan atau akun pelanggan tidak aktif.',
                ])
                ->withInput();
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect('/dashboard-pelanggan');
    }

    // =========================
    // LOGOUT
    // =========================

    public function logout(Request $request)
    {
        $role = Auth::user()?->role;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'pelanggan') {
            return redirect('/login-pelanggan');
        }

        return redirect('/login');
    }
}