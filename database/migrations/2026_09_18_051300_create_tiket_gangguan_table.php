<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket_gangguan', function (Blueprint $table) {
            $table->id();
            $table->string('id_tiket')->unique();
            $table->foreignId('laporan_gangguan_id')
                ->constrained('laporan_gangguan')
                ->cascadeOnDelete();
            $table->foreignId('pelanggan_id')
                ->constrained('pelanggans')
                ->cascadeOnDelete();
            $table->string('jenis_gangguan');
            $table->string('prioritas')->default('sedang');
            $table->string('status')->default('menunggu_teknisi');
            $table->date('tanggal_tiket');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_gangguan');
    }
};