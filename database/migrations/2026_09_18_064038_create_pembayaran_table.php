<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran')->unique();
            $table->foreignId('tagihan_id')
                ->constrained('tagihan')
                ->cascadeOnDelete();
            $table->foreignId('pelanggan_id')
                ->constrained('pelanggans')
                ->cascadeOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->string('metode');
            $table->date('tanggal_pembayaran');
            $table->string('bukti_pembayaran')->nullable();
            $table->text('catatan_pelanggan')->nullable();
            $table->string('status')->default('menunggu_verifikasi');
            $table->foreignId('diverifikasi_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};