<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_gangguan', function (Blueprint $table) {
            $table->id();
            $table->string('id_laporan')->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->string('jenis_gangguan');
            $table->text('deskripsi');
            $table->string('prioritas')->default('sedang');
            $table->string('status')->default('baru');
            $table->date('tanggal_laporan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_gangguan');
    }
};