<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemasok;

class PemasokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pemasokData = [
            [
                'nama_pemasok' => 'PT Sumber Rezeki',
                'alamat' => 'Jl. Kebon Jeruk No. 12, Jakarta',
                'telepon' => '021-567890',
                'email' => 'sumberrezeki@example.com',
                'catatan' => 'Pemasok bahan baku utama',
            ],
            [
                'nama_pemasok' => 'CV Makmur Jaya',
                'alamat' => 'Jl. Cempaka No. 34, Bandung',
                'telepon' => '022-876543',
                'email' => 'makmurjaya@example.com',
                'catatan' => 'Distributor peralatan industri',
            ],
            [
                'nama_pemasok' => 'UD Sentosa',
                'alamat' => 'Jl. Rajawali No. 56, Surabaya',
                'telepon' => '031-234567',
                'email' => 'sentosa@example.com',
                'catatan' => 'Pemasok alat elektronik',
            ],
        ];

        foreach ($pemasokData as $data) {
            Pemasok::create($data);
        }
    }
}