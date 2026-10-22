<?php

namespace App\Http\Controllers;

use App\Models\PenugasanTeknisi;
use App\Models\Perbaikan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerbaikanTeknisiController extends Controller
{
    public function show($id)
    {
        $teknisi = Auth::user()->teknisi;

        $tugas = PenugasanTeknisi::with([
            'tiketGangguan.pelanggan.user',
            'tiketGangguan.pelanggan.paket',
            'tiketGangguan.laporanGangguan',
            'teknisi',
            'perbaikan'
        ])
        ->where(
            'teknisi_id',
            $teknisi->id
        )
        ->findOrFail($id);

        $jadwalKunjungan = \App\Models\JadwalKunjungan::where(
            'penugasan_teknisi_id',
            $tugas->id
        )
        ->where(
            'teknisi_id',
            $teknisi->id
        )
        ->first();

        $perbaikan = $tugas->perbaikan;

        $pelanggan = $tugas
            ->tiketGangguan
            ->pelanggan;

        $userPelanggan = $pelanggan?->user;

        return view(
            'teknisi.perbaikan',
            compact(
                'tugas',
                'perbaikan',
                'jadwalKunjungan',
                'pelanggan',
                'userPelanggan'
            )
        );
    }

    public function store(Request $request, $id)
    {
        $teknisi = Auth::user()->teknisi;

        $request->validate([
            'status' =>
                'required|in:menunggu,perjalanan,ditangani,selesai',

            'keluhan' =>
                'nullable|string',

            'tindakan' =>
                'nullable|string',

            'catatan' =>
                'nullable|string',

            'bukti_foto' =>
                'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $tugas = PenugasanTeknisi::with([
            'tiketGangguan.laporanGangguan',
            'tiketGangguan.pelanggan',
        ])
        ->where(
            'teknisi_id',
            $teknisi->id
        )
        ->findOrFail($id);

        $laporan =
            $tugas
                ->tiketGangguan
                ->laporanGangguan;

        /*
        |--------------------------------------------------------------------------
        | DATA PERBAIKAN
        |--------------------------------------------------------------------------
        */

        $perbaikan = Perbaikan::firstOrNew([
            'penugasan_teknisi_id' =>
                $tugas->id,
        ]);

        $perbaikan->teknisi_id =
            $teknisi->id;

        $perbaikan->pelanggan_id =
            $tugas
                ->tiketGangguan
                ->pelanggan_id;

        $perbaikan->tanggal_perbaikan =
            $perbaikan->tanggal_perbaikan
            ?? today();

        $perbaikan->keluhan =
            $request->keluhan
            ?: $laporan?->deskripsi;

        $perbaikan->tindakan =
            $request->tindakan;

        $perbaikan->status =
            $request->status;

        $perbaikan->catatan =
            $request->catatan;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO BUKTI
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('bukti_foto')) {

            if ($perbaikan->bukti_foto) {

                Storage::disk('public')
                    ->delete(
                        $perbaikan->bukti_foto
                    );
            }

            $perbaikan->bukti_foto =
                $request
                    ->file('bukti_foto')
                    ->store(
                        'bukti-perbaikan',
                        'public'
                    );
        }

        $perbaikan->save();

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI KE PELANGGAN
        |--------------------------------------------------------------------------
        */

        $pelangganUser =
            $tugas
                ->tiketGangguan
                ->pelanggan
                ?->user;

        if ($pelangganUser) {

            Notifikasi::create([
                'user_id' =>
                    $pelangganUser->id,

                'judul' =>
                    'Update Perbaikan',

                'pesan' =>
                    'Status perbaikan gangguan Anda sekarang: ' .
                    $request->status,

                'tipe' =>
                    'perbaikan',

                'referensi_tipe' =>
                    'perbaikan',

                'referensi_id' =>
                    $perbaikan->id,

                'dibaca' =>
                    false,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS TUGAS
        |--------------------------------------------------------------------------
        */

        $tugas->update([
            'status' =>
                $request->status === 'selesai'
                ? 'selesai'
                : 'diproses',
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        if ($request->status === 'selesai') {

            return redirect(
                '/tugas-teknisi'
            )->with(
                'success',
                'Perbaikan berhasil diselesaikan.'
            );
        }

        return redirect(
            '/perbaikan-teknisi/' .
            $tugas->id
        )->with(
            'success',
            'Perubahan perbaikan berhasil disimpan.'
        );
    }
}