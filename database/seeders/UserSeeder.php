<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Alfa Net',
            'email' => 'admin@alfanet.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Budi Teknisi',
            'email' => 'teknisi@alfanet.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);

        User::create([
            'name' => 'Andi Pelanggan',
            'email' => 'pelanggan@alfanet.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
        ]);
    }
}