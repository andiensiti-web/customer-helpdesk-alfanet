<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    protected $fillable = [
        'notifikasi_laporan_teknisi',
        'notifikasi_pembayaran',
        'notifikasi_tagihan',
    ];

    protected $casts = [
        'notifikasi_laporan_teknisi' => 'boolean',
        'notifikasi_pembayaran' => 'boolean',
        'notifikasi_tagihan' => 'boolean',
    ];
}