<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_teknisi', function (Blueprint $table) {
            $table->id();
            $table->string('id_laporan')->unique();

            $table->foreignId('penugasan_teknisi_id')
                ->constrained('penugasan_teknisi')
                ->cascadeOnDelete();

            $table->foreignId('teknisi_id')
                ->constrained('teknisi')
                ->cascadeOnDelete();

            $table->foreignId('pelanggan_id')
                ->constrained('pelanggans')
                ->cascadeOnDelete();

            $table->text('hasil_perbaikan');
            $table->text('tindakan')->nullable();
            $table->string('status')->default('menunggu_approval');
            $table->date('tanggal_laporan');
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_teknisi');
    }
};