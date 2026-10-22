<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\LaporanGangguanController;
use App\Http\Controllers\TiketGangguanController;
use App\Http\Controllers\PenugasanTeknisiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanTeknisiController;
use App\Http\Controllers\LaporanAdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeknisiDashboardController;
use App\Http\Controllers\TugasTeknisiController;
use App\Http\Controllers\DetailTugasTeknisiController;
use App\Http\Controllers\PerbaikanTeknisiController;
use App\Http\Controllers\PelangganTeknisiController;
use App\Http\Controllers\JadwalKunjunganTeknisiController;
use App\Http\Controllers\PengaturanTeknisiController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\PelangganDashboardController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\MetodePembayaranController;



/* =========================
   AUTH
========================= */

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login-pelanggan', [AuthController::class, 'showLoginPelanggan']);
Route::post('/login-pelanggan', [AuthController::class, 'loginPelanggan']);

/* =========================
   LUPA PASSWORD
========================= */

Route::get('/lupa-password', [PasswordResetController::class, 'showForgotPassword'])->name('password.request');
Route::post('/lupa-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');


/* =========================
   NOTIFIKASI
========================= */

Route::middleware('auth')->group(function () {
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::get('/notifikasi/{id}', [NotifikasiController::class, 'baca']);
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua']);
});


/* =========================
   PUSH NOTIFICATION
========================= */

Route::middleware('auth')->group(function () {
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store']);
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy']);
});


/* =========================
   ADMIN
========================= */

Route::middleware(['auth', 'role:admin'])->group(function () {

    /* Dashboard */

    Route::get('/dashboard', [DashboardController::class, 'index']);


    /* Pelanggan */

    Route::get('/pelanggan-admin', [PelangganController::class, 'index']);
    Route::get('/tambah-pelanggan', [PelangganController::class, 'create']);
    Route::post('/tambah-pelanggan', [PelangganController::class, 'store']);
    Route::get('/edit-pelanggan/{id}', [PelangganController::class, 'edit']);
    Route::put('/edit-pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/hapus-pelanggan/{id}', [PelangganController::class, 'destroy']);
    Route::get('/pelanggan-admin/export', [PelangganController::class, 'export']);
    Route::post('/pelanggan-admin/import', [PelangganController::class, 'import'])->name('pelanggan.import');
    Route::get('/pelanggan-admin/template', [PelangganController::class,'downloadTemplate'])->name('pelanggan.template');
    Route::delete('/pelanggan-admin/hapus-massal', [PelangganController::class,'hapusMassal']);

    /* Teknisi */

    Route::get('/teknisi-admin', [TeknisiController::class, 'index']);
    Route::get('/tambah-teknisi', [TeknisiController::class, 'create']);
    Route::post('/tambah-teknisi', [TeknisiController::class, 'store']);
    Route::get('/edit-teknisi/{id}', [TeknisiController::class, 'edit']);
    Route::put('/edit-teknisi/{id}', [TeknisiController::class, 'update']);
    Route::delete('/hapus-teknisi/{id}', [TeknisiController::class, 'destroy']);


    /* Gangguan */

    Route::get('/gangguan-admin', [LaporanGangguanController::class, 'index']);
    Route::get('/gangguan-detail/{id}', [LaporanGangguanController::class, 'show']);
    Route::get('/gangguan-verifikasi/{id}', [LaporanGangguanController::class, 'verify']);
    Route::put('/gangguan-setujui/{id}', [LaporanGangguanController::class, 'approve']);
    Route::put('/gangguan-tolak/{id}', [LaporanGangguanController::class, 'reject']);


    /* Tiket & Penugasan Teknisi */

    Route::get('/tambah-tiket/{id}', [TiketGangguanController::class, 'create']);
    Route::post('/tambah-tiket/{id}', [TiketGangguanController::class, 'store']);

    Route::get('/penugasan-teknisi', [PenugasanTeknisiController::class, 'index']);
    Route::get('/detail-penugasan/{id}', [PenugasanTeknisiController::class, 'create']);
    Route::post('/detail-penugasan/{id}', [PenugasanTeknisiController::class, 'store']);
    Route::get('/detail-teknisi/{id}', [PenugasanTeknisiController::class, 'detail']);


    /* Pembayaran */

    Route::get('/pembayaran-admin', [PembayaranController::class, 'index']);
    Route::get('/catat-pembayaran', [PembayaranController::class, 'create']);
    Route::post('/catat-pembayaran', [PembayaranController::class, 'store']);
    Route::get('/detail-pembayaran/{id}', [PembayaranController::class, 'show']);
    Route::post('/verifikasi-pembayaran/{id}', [PembayaranController::class,'verify'])->middleware('role:admin');
    Route::put('/pembayaran-tolak/{id}', [PembayaranController::class, 'reject']);
    Route::get('/pembayaran-admin', [PembayaranController::class,'index'])->middleware('auth');
    Route::get('/detail-pembayaran-admin/{id}', [PembayaranController::class,'show'])->middleware('auth');
    Route::post('/verifikasi-pembayaran/{id}', [PembayaranController::class,'verifikasi'])->middleware(['auth','role:admin']);
    Route::post('/tolak-pembayaran/{id}', [PembayaranController::class,'reject']);
    Route::get('/print-pembayaran/{id}', [PembayaranController::class,'print']);
    

    /* Tagihan */

    Route::get('/tagihan-admin', [TagihanController::class, 'index']);
    Route::get('/tambah-tagihan', [TagihanController::class, 'create']);
    Route::post('/tambah-tagihan', [TagihanController::class, 'store']);
    Route::get('/detail-tagihan/{id}', [TagihanController::class, 'show']);
    Route::post('/generate-tagihan', [TagihanController::class, 'generate']);


    /* Laporan Admin */

    Route::get('/laporan-admin', [LaporanAdminController::class, 'index'])->middleware(['auth','role:admin']);
    Route::get('/laporan-menunggu', [LaporanAdminController::class, 'menunggu']);
    Route::get('/laporan-detail/{id}', [LaporanAdminController::class, 'show']);
    Route::put('/laporan-setujui/{id}', [LaporanAdminController::class, 'approve']);
    Route::put('/laporan-tolak/{id}', [LaporanAdminController::class, 'reject']);
    Route::get('/laporan-disetujui', [LaporanAdminController::class, 'disetujui']);
    Route::get('/laporan-ditolak', [LaporanAdminController::class, 'ditolak']);
    Route::get('/laporan-keuangan', [LaporanAdminController::class, 'keuangan']);
    Route::get('/laporan-print/{id}', [LaporanAdminController::class, 'print']);
    Route::get('/laporan-export-pdf', [LaporanAdminController::class, 'exportPdf'])->name('laporan-pdf');;
    Route::get('/laporan-pdf-satuan/{id}', [LaporanAdminController::class,'print']);


    /* Akun */

    Route::get('/akun-admin', [AkunController::class, 'index']);
    Route::get('/tambah-akun', [AkunController::class, 'create']);
    Route::post('/tambah-akun', [AkunController::class, 'store']);
    Route::get('/edit-akun/{id}', [AkunController::class, 'edit']);
    Route::put('/edit-akun/{id}', [AkunController::class, 'update']);
    Route::put('/reset-password-akun/{id}', [AkunController::class, 'resetPassword']);
    Route::delete('/hapus-akun/{id}', [AkunController::class, 'destroy']);


   /* Pengaturan */
    Route::middleware('auth')->group(function () {
    Route::get('/pengaturan', [PengaturanController::class, 'index']);
    Route::get('/pengaturan/akun', [PengaturanController::class, 'akun']);
    Route::get('/pengaturan/notifikasi', [PengaturanController::class, 'notifikasi']);
    Route::post('/pengaturan/notifikasi', [PengaturanController::class, 'update']);
    Route::get('/pengaturan/paket', [PengaturanController::class, 'paket']);
    Route::post('/pengaturan/paket', [PengaturanController::class, 'storePaket']);
    Route::put('/pengaturan/paket/{id}', [PengaturanController::class, 'updatePaket']);
    Route::delete('/pengaturan/paket/{id}', [PengaturanController::class, 'destroyPaket']);
    Route::get('/pengaturan/pembayaran', [MetodePembayaranController::class, 'index']);
    Route::post('/pengaturan/pembayaran', [MetodePembayaranController::class, 'store']);
    Route::put('/pengaturan/pembayaran/{id}', [MetodePembayaranController::class, 'update']);
    Route::delete('/pengaturan/pembayaran/{id}', [MetodePembayaranController::class, 'destroy']);
    Route::post('/pengaturan/perusahaan', [PengaturanController::class, 'updatePerusahaan'])->name('pengaturan-perusahaan.update');

    });
});

/* =========================
   TEKNISI
========================= */

Route::middleware(['auth', 'role:teknisi'])->group(function () {

    Route::get('/dashboard-teknisi', [TeknisiDashboardController::class, 'index']);

    Route::get('/tugas-teknisi', [TugasTeknisiController::class, 'index']);

    Route::get('/detail-tugas/{id}', [DetailTugasTeknisiController::class, 'show']);
    Route::post('/detail-tugas/{id}/mulai', [DetailTugasTeknisiController::class, 'mulai']);

    Route::get('/perbaikan-teknisi/{id}', [PerbaikanTeknisiController::class, 'show']);
    Route::post('/perbaikan-teknisi/{id}', [PerbaikanTeknisiController::class, 'store']);
    Route::post('/perbaikan-teknisi/{id}/status', [PerbaikanTeknisiController::class, 'updateStatus']);

    Route::get('/pelanggan-teknisi', [PelangganTeknisiController::class, 'index']);
    Route::get('/detail-pelanggan/{id}', [PelangganTeknisiController::class, 'show']);

    Route::get('/jadwal-kunjungan', [JadwalKunjunganTeknisiController::class, 'index']);

    Route::get('/laporan-teknisi', [LaporanTeknisiController::class, 'index']);
    Route::get('/buat-laporan-teknisi/{id}', [LaporanTeknisiController::class, 'create']);
    Route::post('/buat-laporan-teknisi/{id}', [LaporanTeknisiController::class, 'store']);

    Route::get('/pengaturan-teknisi', [PengaturanTeknisiController::class, 'index']);
    Route::post('/pengaturan-teknisi', [PengaturanTeknisiController::class, 'update']);
});


/* =========================
   PELANGGAN
========================= */

Route::middleware(['auth', 'role:pelanggan'])->group(function () {

    /* Dashboard */

    Route::get('/dashboard-pelanggan',[PelangganDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard-pelanggan');


    /* Laporan Gangguan */

    Route::get('/laporan-gangguan', [LaporanGangguanController::class, 'create']);
    Route::post('/laporan-gangguan', [LaporanGangguanController::class, 'store']);


    /* Tagihan */

    Route::get('/tagihan-pelanggan', [TagihanController::class, 'pelanggan']);


    /* Pembayaran */

    Route::get('/pembayaran-pelanggan/{id}',[PembayaranController::class,'createPelanggan'])
        ->middleware('auth');
    Route::post('/pembayaran-pelanggan',[PembayaranController::class,'storePelanggan'])
        ->middleware('auth');
    Route::get('/riwayat-pembayaran',[PembayaranController::class,'riwayat'])
        ->middleware('auth');
    Route::get('/detail-pembayaran/{id}',[PembayaranController::class,'detailPelanggan'])
        ->middleware('auth');
    Route::get('/perbaiki-pembayaran/{id}', [PembayaranController::class, 'perbaikiPembayaran'])
        ->middleware('auth');
    Route::post('/perbaiki-pembayaran/{id}', [PembayaranController::class, 'updateBuktiPembayaran'])
        ->middleware('auth');
    Route::get('/pembayaran-verifikasi/{id}', [ PembayaranController::class, 'verifikasi']);
    Route::get('/admin/detail-pembayaran/{id}', [PembayaranController::class, 'detailAdmin'])->middleware(['auth', 'role:admin']);
    Route::get('/invoice-pembayaran/{id}', [PembayaranController::class, 'invoicePelanggan']);
    

    /* Pengaturan */

    Route::get('/pengaturan-pelanggan', function () {
        return view('pelanggan.pengaturan-pelanggan');
    });
});