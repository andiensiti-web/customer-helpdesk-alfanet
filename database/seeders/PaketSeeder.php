<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        Paket::create([
            'kode_paket' => 'PKT-001',
            'nama_paket' => 'Alfa Home 10',
            'kecepatan' => '10 Mbps',
            'harga_bulanan' => 150000,
            'deskripsi' => 'Paket internet rumah 10 Mbps',
            'status_paket' => 'aktif',
        ]);

        Paket::create([
            'kode_paket' => 'PKT-002',
            'nama_paket' => 'Alfa Home 20',
            'kecepatan' => '20 Mbps',
            'harga_bulanan' => 200000,
            'deskripsi' => 'Paket internet rumah 20 Mbps',
            'status_paket' => 'aktif',
        ]);

        Paket::create([
            'kode_paket' => 'PKT-003',
            'nama_paket' => 'Alfa Home 50',
            'kecepatan' => '50 Mbps',
            'harga_bulanan' => 300000,
            'deskripsi' => 'Paket internet rumah 50 Mbps',
            'status_paket' => 'aktif',
        ]);
    }
}