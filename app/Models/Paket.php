<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'pakets';

    protected $fillable = [
        'nama_paket',
        'kecepatan',
        'harga_bulanan',
        'status_paket',
    ];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'paket_id');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'paket_id');
    }
}