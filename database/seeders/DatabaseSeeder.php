<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        $this->call(PelangganSeeder::class);
        $this->call(PemasokSeeder::class);
=======

        $this->call(PelangganSeeder::class);
        $this->call(PemasokSeeder::class);
        $this->call(UserSeeder::class);
>>>>>>> e5d46b5 (Menambahkan project baru)

    }
}
