<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
        /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */
            $table->boolean('laporan_teknisi')
                ->default(true);
            $table->boolean('pembayaran')
                ->default(true);

            $table->boolean('gangguan')
                ->default(true);

            $table->boolean('penugasan')
                ->default(true);

            $table->timestamps();
        });

    }





    public function down(): void
    {

        Schema::dropIfExists(
            'notification_settings'
        );

    }


};