<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_teknisi_id')->constrained('penugasan_teknisi')->cascadeOnDelete();
            $table->foreignId('teknisi_id')->constrained('teknisi')->cascadeOnDelete();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->date('tanggal_perbaikan');
            $table->text('keluhan')->nullable();
            $table->text('tindakan')->nullable();
            $table->string('status')->default('belum_mulai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};