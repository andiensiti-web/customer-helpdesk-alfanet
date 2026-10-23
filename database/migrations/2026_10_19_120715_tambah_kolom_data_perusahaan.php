
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_perusahaans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengaturan_perusahaans', 'nama_perusahaan')) {
                $table->string('nama_perusahaan')->nullable();
            }

            if (!Schema::hasColumn('pengaturan_perusahaans', 'alamat_perusahaan')) {
                $table->text('alamat_perusahaan')->nullable();
            }

            if (!Schema::hasColumn('pengaturan_perusahaans', 'telepon_perusahaan')) {
                $table->string('telepon_perusahaan', 30)->nullable();
            }

            if (!Schema::hasColumn('pengaturan_perusahaans', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable();
            }

            if (!Schema::hasColumn('pengaturan_perusahaans', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_perusahaans', function (Blueprint $table) {
            $columns = [
                'nama_perusahaan',
                'alamat_perusahaan',
                'telepon_perusahaan',
                'latitude',
                'longitude',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('pengaturan_perusahaans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
