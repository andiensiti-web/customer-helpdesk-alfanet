<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Perbaikan;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where(
            'user_id',
            Auth::id()
        )
        ->latest()
        ->paginate(15);

        $jumlahBelumDibaca = Notifikasi::where(
            'user_id',
            Auth::id()
        )
        ->where(
            'dibaca',
            false
        )
        ->count();

        return view(
            'notifikasi.index',
            compact(
                'notifikasi',
                'jumlahBelumDibaca'
            )
        );
    }

    public function baca($id)
    {
        $notifikasi = Notifikasi::where(
            'user_id',
            Auth::id()
        )->findOrFail($id);

        $notifikasi->update([
            'dibaca' => true,
        ]);

        $role = Auth::user()->role;

        /*
        |--------------------------------------------------------------------------
        | ADMIN - LAPORAN TEKNISI
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'admin' &&
            $notifikasi->referensi_tipe === 'laporan_teknisi' &&
            $notifikasi->referensi_id
        ) {
            return redirect(
                '/laporan-detail/' .
                $notifikasi->referensi_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TEKNISI - PENUGASAN
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'teknisi' &&
            $notifikasi->referensi_tipe === 'penugasan_teknisi' &&
            $notifikasi->referensi_id
        ) {
            return redirect(
                '/detail-tugas/' .
                $notifikasi->referensi_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PELANGGAN - TAGIHAN
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'pelanggan' &&
            $notifikasi->referensi_tipe === 'tagihan' &&
            $notifikasi->referensi_id
        ) {
            return redirect(
                '/detail-tagihan/' .
                $notifikasi->referensi_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TEKNISI - JADWAL KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'teknisi' &&
            $notifikasi->referensi_tipe === 'jadwal_kunjungan'
        ) {
            return redirect(
                '/jadwal-kunjungan'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PELANGGAN - PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'pelanggan' &&
            $notifikasi->referensi_tipe === 'pembayaran' &&
            $notifikasi->referensi_id
        ) {
            return redirect(
                '/detail-pembayaran/' .
                $notifikasi->referensi_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GANGGUAN
        |--------------------------------------------------------------------------
        */

        if (
            $notifikasi->referensi_tipe === 'gangguan' &&
            $notifikasi->referensi_id
        ) {
            return redirect(
                '/gangguan-detail/' .
                $notifikasi->referensi_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TEKNISI - PERBAIKAN
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'teknisi' &&
            $notifikasi->referensi_tipe === 'perbaikan' &&
            $notifikasi->referensi_id
        ) {
            $perbaikan = Perbaikan::find(
                $notifikasi->referensi_id
            );

            if ($perbaikan) {
                return redirect(
                    '/perbaikan-teknisi/' .
                    $perbaikan->penugasan_teknisi_id
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN - PENGINGAT TAGIHAN
        |--------------------------------------------------------------------------
        */

        if (
            $role === 'admin' &&
            $notifikasi->referensi_tipe === 'pengingat_tagihan' &&
            $notifikasi->referensi_id
        ) {
            return redirect(
                '/tambah-tagihan?pelanggan=' .
                $notifikasi->referensi_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */

        return redirect('/notifikasi');
    }

    public function bacaSemua()
    {
        Notifikasi::where(
            'user_id',
            Auth::id()
        )
        ->where(
            'dibaca',
            false
        )
        ->update([
            'dibaca' => true,
        ]);

        return back()->with(
            'success',
            'Semua notifikasi telah dibaca.'
        );
    }
}