<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKunjungan extends Model
{
    protected $table = 'jadwal_kunjungan';

    protected $fillable = [
        'penugasan_teknisi_id',
        'teknisi_id',
        'pelanggan_id',
        'tanggal_kunjungan',
        'jam_kunjungan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function penugasan()
    {
        return $this->belongsTo(
            PenugasanTeknisi::class,
            'penugasan_teknisi_id'
        );
    }

    public function teknisi()
    {
        return $this->belongsTo(
            Teknisi::class,
            'teknisi_id'
        );
    }

    public function pelanggan()
    {
        return $this->belongsTo(
            Pelanggan::class,
            'pelanggan_id'
        );
    }
}