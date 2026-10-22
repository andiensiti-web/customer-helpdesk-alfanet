<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = [
        'kode_tagihan',
        'pelanggan_id',
        'paket_id',
        'jumlah',
        'tanggal_terbit',
        'tanggal_jatuh_tempo',
        'status',
        'catatan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_terbit' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}