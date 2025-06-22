<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data meja yang sudah ada untuk menghindari duplikat
        Table::query()->delete();

        // Membuat 10 meja baru
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'table_number' => $i,
            ]);
        }
    }
}
