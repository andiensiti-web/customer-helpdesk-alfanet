<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {

            $table->boolean('tugas_baru')
                ->default(true)
                ->after('penugasan');

            $table->boolean('jadwal_kunjungan')
                ->default(true)
                ->after('tugas_baru');

            $table->boolean('laporan_disetujui')
                ->default(true)
                ->after('jadwal_kunjungan');

        });
    }


    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {

            $table->dropColumn([
                'tugas_baru',
                'jadwal_kunjungan',
                'laporan_disetujui'
            ]);

        });
    }

};