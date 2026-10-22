<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_teknisi_id')->constrained('penugasan_teknisi')->cascadeOnDelete();
            $table->foreignId('teknisi_id')->constrained('teknisi')->cascadeOnDelete();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->date('tanggal_kunjungan');
            $table->time('jam_kunjungan')->nullable();
            $table->string('status')->default('terjadwal');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kunjungan');
    }
};