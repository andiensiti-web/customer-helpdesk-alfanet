<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationSetting;


class PengaturanTeknisiController extends Controller
{

    public function index()
    {
        $user = Auth::user();


        $setting = NotificationSetting::firstOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'laporan_teknisi' => true,
                'pembayaran' => true,
                'gangguan' => true,
                'penugasan' => true,
                'tugas_baru' => true,
                'jadwal_kunjungan' => true,
                'laporan_disetujui' => true,
            ]
        );


        $teknisi = $user->teknisi;


        return view(
            'teknisi.pengaturan-teknisi',
            compact(
                'user',
                'teknisi',
                'setting'
            )
        );
    }




    public function update(Request $request)
    {
        $user = Auth::user();


        $setting = NotificationSetting::firstOrCreate(
            [
                'user_id' => $user->id
            ]
        );


        $setting->update([

            'tugas_baru' =>
                $request->has('tugas_baru'),


            'jadwal_kunjungan' =>
                $request->has('jadwal_kunjungan'),


            'laporan_disetujui' =>
                $request->has('laporan_disetujui'),

        ]);



        return back()->with(
            'success',
            'Pengaturan notifikasi berhasil diperbarui.'
        );
    }

}