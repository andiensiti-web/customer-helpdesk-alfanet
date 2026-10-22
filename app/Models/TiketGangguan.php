<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketGangguan extends Model
{
    protected $table = 'tiket_gangguan';

    protected $fillable = [
        'id_tiket',
        'laporan_gangguan_id',
        'pelanggan_id',
        'jenis_gangguan',
        'prioritas',
        'status',
        'tanggal_tiket',
        'catatan',
    ];

    protected $casts = [
        'tanggal_tiket' => 'date',
    ];

    public function laporanGangguan()
    {
        return $this->belongsTo(LaporanGangguan::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function penugasan()
    {
    return $this->hasOne(PenugasanTeknisi::class, 'tiket_gangguan_id');
    }
}