<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pelanggans', 'nama')) {
            Schema::table('pelanggans', function (Blueprint $table) {
                $table->string('nama')->after('id_pelanggan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pelanggans', 'nama')) {
            Schema::table('pelanggans', function (Blueprint $table) {
                $table->dropColumn('nama');
            });
        }
    }
};