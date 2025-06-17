<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // <-- Pastikan ini ada

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus user lama jika ada, agar tidak duplikat
        User::where('email', 'admin@mejawarunk.com')->delete();

        // Buat user admin baru
        User::create([
            'name' => 'Admin Warunk',
            'email' => 'admin@mejawarunk.com',
            'password' => Hash::make('password'), // Ganti 'password' dengan password aman Anda
        ]);
    }
}
