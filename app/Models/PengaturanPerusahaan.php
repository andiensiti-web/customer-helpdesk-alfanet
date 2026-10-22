<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPerusahaan extends Model
{
    protected $table = 'pengaturan_perusahaans';

    protected $fillable = [
        'nama_perusahaan',
        'alamat_perusahaan',
        'telepon_perusahaan',
        'latitude',
        'longitude',
    ];
}