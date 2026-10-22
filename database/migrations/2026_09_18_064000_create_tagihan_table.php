<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tagihan')->unique();
            $table->foreignId('pelanggan_id')
                ->constrained('pelanggans')
                ->cascadeOnDelete();
            $table->foreignId('paket_id')
                ->nullable()
                ->constrained('pakets')
                ->nullOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal_terbit');
            $table->date('tanggal_jatuh_tempo');
            $table->string('status')->default('belum_bayar');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};