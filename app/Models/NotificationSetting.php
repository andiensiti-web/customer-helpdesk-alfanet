<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    protected $table = 'notification_settings';
    protected $fillable = [
        'user_id',
        'laporan_teknisi',
        'pembayaran',
        'gangguan',
        'penugasan',
        'tugas_baru',
        'jadwal_kunjungan',
        'laporan_disetujui',
    ];

    protected $casts = [
        'laporan_teknisi' => 'boolean',
        'pembayaran' => 'boolean',
        'gangguan' => 'boolean',
        'penugasan' => 'boolean',
        'tugas_baru' => 'boolean',
        'jadwal_kunjungan' => 'boolean',
        'laporan_disetujui' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

}