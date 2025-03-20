<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelangganData = [
            [
                'kode_pelanggan' => 'PLG001',
                'nama' => 'Ahmad Prasetyo',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'no_telp' => '081234567890',
                'email' => 'ahmad@example.com',
            ],
            [
                'kode_pelanggan' => 'PLG002',
                'nama' => 'Siti Rahmawati',
                'alamat' => 'Jl. Sudirman No. 20, Bandung',
                'no_telp' => '081298765432',
                'email' => 'siti@example.com',
            ],
            [
                'kode_pelanggan' => 'PLG003',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Diponegoro No. 30, Surabaya',
                'no_telp' => '082112345678',
                'email' => 'budi@example.com',
            ],
        ];

        foreach ($pelangganData as $data) {
            Pelanggan::create($data);
        }
    }
}