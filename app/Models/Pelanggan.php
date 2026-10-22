<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'user_id',
        'paket_id',
        'id_pelanggan',
        'nama',
        'tipe_pelanggan',
        'tanggal_mulai',
        'tanggal_jatuh_tempo',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function laporanGangguan()
    {
        return $this->hasMany(
            LaporanGangguan::class,
            'pelanggan_id'
        );
    }

    public function laporanTeknisi()
    {
        return $this->hasMany(
            LaporanTeknisi::class,
            'pelanggan_id'
        );
    }

    public function tagihan()
    {
        return $this->hasMany(
            Tagihan::class,
            'pelanggan_id'
        );
    }

    public function pembayaran()
    {
        return $this->hasMany(
            Pembayaran::class,
            'pelanggan_id'
        );
    }
}