<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_perusahaans', function (Blueprint $table) {
            $table->string('nama_perusahaan')->nullable();
            $table->text('alamat_perusahaan')->nullable();
            $table->string('telepon_perusahaan', 30)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_perusahaan',
                'alamat_perusahaan',
                'telepon_perusahaan',
                'latitude',
                'longitude',
            ]);
        });
    }
};