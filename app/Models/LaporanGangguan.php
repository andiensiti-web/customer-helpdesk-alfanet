<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanGangguan extends Model
{
    protected $table = 'laporan_gangguan';

    protected $fillable = [
        'id_laporan',
        'pelanggan_id',
        'jenis_gangguan',
        'deskripsi',
        'prioritas',
        'status',
        'tanggal_laporan',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function tiketGangguan()
    {
        return $this->hasOne(
            TiketGangguan::class,
            'laporan_gangguan_id'
        );
    }
}