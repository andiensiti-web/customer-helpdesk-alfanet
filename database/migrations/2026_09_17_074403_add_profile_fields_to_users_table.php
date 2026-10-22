<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('telepon')->nullable()->after('role');
            $table->text('alamat')->nullable()->after('telepon');
            $table->string('status')->default('aktif')->after('alamat');
            $table->timestamp('last_login_at')->nullable()->after('status');
        });

        DB::table('users')->where('id', 1)->update([
            'username' => 'admin'
        ]);

        DB::table('users')->where('id', 2)->update([
            'username' => 'budi'
        ]);

        DB::table('users')->where('id', 3)->update([
            'username' => 'andi'
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'telepon',
                'alamat',
                'status',
                'last_login_at',
            ]);
        });
    }
};