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
        // Memanggil seeder lain secara berurutan
        $this->call([
            // PERBAIKAN: Menggunakan nama seeder yang benar sesuai file Anda
            AdminUserSeeder::class,
            TableSeeder::class,
            MenuSeeder::class,
            DummyDataSeeder::class,]);
    }
}

