<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    protected $table = 'teknisi';

    protected $fillable = [
    'user_id',
    'id_teknisi',
    'nama',
    'email',
    'telepon',
    'keahlian',
    'status',
    'alamat',
    'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function laporanTeknisi()
    {
    return $this->hasMany(LaporanTeknisi::class, 'teknisi_id');
    }

    public function jadwalKunjungan()
    {
    return $this->hasMany(JadwalKunjungan::class, 'teknisi_id');
    }
}