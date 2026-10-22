<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teknisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('id_teknisi')->unique();
            $table->string('nama');
            $table->string('email');
            $table->string('telepon');
            $table->string('keahlian')->nullable();
            $table->string('status')->default('aktif');
            $table->text('alamat')->nullable();
            $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('teknisi');
    }
};