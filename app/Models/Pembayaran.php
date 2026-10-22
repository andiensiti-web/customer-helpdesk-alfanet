<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'kode_pembayaran',
        'tagihan_id',
        'pelanggan_id',
        'jumlah',
        'metode',
        'tanggal_pembayaran',
        'bukti_pembayaran',
        'catatan_pelanggan',
        'catatan_verifikasi',
        'status',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];


    public function pelanggan()
    {
        return $this->belongsTo(
            Pelanggan::class,
            'pelanggan_id'
        );
    }


    public function tagihan()
    {
        return $this->belongsTo(
            Tagihan::class,
            'tagihan_id'
        );
    }

    public function verifier()
    {
    return $this->belongsTo(
        User::class,
        'diverifikasi_oleh'
    );
    }

    protected $casts = [
    'tanggal_pembayaran' => 'date',
    'tanggal_verifikasi' => 'datetime',
];
}