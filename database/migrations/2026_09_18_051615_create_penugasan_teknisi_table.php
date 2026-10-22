<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasan_teknisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tiket_gangguan_id')
                ->constrained('tiket_gangguan')
                ->cascadeOnDelete();
            $table->foreignId('teknisi_id')
                ->constrained('teknisi')
                ->cascadeOnDelete();
            $table->date('tanggal_penugasan');
            $table->string('status')->default('ditugaskan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_teknisi');
    }
};