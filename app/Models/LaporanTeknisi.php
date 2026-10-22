<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTeknisi extends Model
{
    protected $table = 'laporan_teknisi';

    protected $fillable = [
        'id_laporan',
        'penugasan_teknisi_id',
        'teknisi_id',
        'pelanggan_id',
        'hasil_perbaikan',
        'tindakan',
        'status',
        'tanggal_laporan',
        'catatan',
    ];

    protected $casts = [
    'tanggal_laporan' => 'datetime',
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