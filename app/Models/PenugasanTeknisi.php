<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanTeknisi extends Model
{
    protected $table = 'penugasan_teknisi';

    protected $fillable = [
        'tiket_gangguan_id',
        'teknisi_id',
        'tanggal_penugasan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_penugasan' => 'date',
    ];

    public function tiketGangguan()
    {
    return $this->belongsTo(
        TiketGangguan::class,
        'tiket_gangguan_id'
    );
    }

    public function teknisi()
    {
    return $this->belongsTo(
        Teknisi::class,
        'teknisi_id'
    );
    }

    public function laporanTeknisi()
    {
    return $this->hasOne(
        LaporanTeknisi::class,
        'penugasan_teknisi_id'
    );
    }

    public function perbaikan()
    {
    return $this->hasOne(Perbaikan::class, 'penugasan_teknisi_id');
    }
}