<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    protected $table = 'perbaikan';

    protected $fillable = [
        'penugasan_teknisi_id',
        'teknisi_id',
        'pelanggan_id',
        'tanggal_perbaikan',
        'keluhan',
        'tindakan',
        'status',
        'catatan',
        'bukti_foto',
    ];

    protected $casts = [
        'tanggal_perbaikan' => 'date',
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