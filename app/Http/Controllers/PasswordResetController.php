<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PasswordResetController extends Controller
{
    // Menampilkan halaman lupa password
    public function showForgotPassword()
    {
        return view('auth.lupa-password');
    }

    // Memproses email yang dimasukkan
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Cari akun berdasarkan email
        $user = User::where('email', $request->email)
            ->where('status', 'aktif')
            ->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Email tidak ditemukan atau akun tidak aktif.',
                ])
                ->withInput();
        }

        // Membuat token
        $token = Str::random(64);

        // Simpan token ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // Untuk sementara langsung menuju halaman reset password
        return redirect(
            '/reset-password/' . $token .
            '?email=' . urlencode($user->email)
        );
    }

    // Menampilkan halaman reset password
    public function showResetPassword(Request $request, string $token)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect('/lupa-password')
                ->withErrors([
                    'email' => 'Link reset password tidak valid.',
                ]);
        }

        // Cari token berdasarkan email
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$reset) {
            return redirect('/lupa-password')
                ->withErrors([
                    'email' => 'Link reset password tidak valid atau sudah digunakan.',
                ]);
        }

        // Cek masa berlaku token (60 menit)
        if (Carbon::parse($reset->created_at)
            ->addMinutes(60)
            ->isPast()) {

            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return redirect('/lupa-password')
                ->withErrors([
                    'email' => 'Link reset password sudah kedaluwarsa.',
                ]);
        }

        // Cek apakah token benar
        if (!Hash::check($token, $reset->token)) {
            return redirect('/lupa-password')
                ->withErrors([
                    'email' => 'Link reset password tidak valid.',
                ]);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Menyimpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        // Cari token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return back()->withErrors([
                'password' => 'Link reset password tidak valid.',
            ]);
        }

        // Cek masa berlaku
        if (Carbon::parse($reset->created_at)
            ->addMinutes(60)
            ->isPast()) {

            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return redirect('/lupa-password')
                ->withErrors([
                    'email' => 'Link reset password sudah kedaluwarsa.',
                ]);
        }

        // Cek token
        if (!Hash::check($request->token, $reset->token)) {
            return back()->withErrors([
                'password' => 'Link reset password tidak valid.',
            ]);
        }

        // Cari user
        $user = User::where('email', $request->email)
            ->where('status', 'aktif')
            ->first();

        if (!$user) {
            return redirect('/lupa-password')
                ->withErrors([
                    'email' => 'Akun tidak ditemukan atau tidak aktif.',
                ]);
        }

        // Simpan password baru
        $user->password = $request->password;
        $user->save();

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect('/login')->with(
            'success',
            'Password berhasil diubah. Silakan login menggunakan password baru.'
        );
    }
}