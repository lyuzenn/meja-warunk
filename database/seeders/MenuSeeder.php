<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menghapus data menu lama untuk menghindari duplikasi
        Menu::query()->delete();

        $menus = [
            // ================== MAKANAN ==================
            [
                'name' => 'Nasi Bakar Ayam Suwir',
                'description' => 'Nasi gurih dibungkus daun pisang lalu dibakar, diisi dengan ayam suwir pedas kemangi.',
                'price' => 25000,
                'category' => 'Makanan',
                'is_available' => true,
            ],
            [
                'name' => 'Sate Taichan',
                'description' => '10 tusuk sate ayam dengan bumbu gurih, disajikan dengan sambal pedas dan perasan jeruk nipis.',
                'price' => 22000,
                'category' => 'Makanan',
                'is_available' => true,
            ],
            [
                'name' => 'Indomie Goreng Special',
                'description' => 'Indomie goreng dengan topping telur ceplok, kornet, dan irisan cabai rawit.',
                'price' => 18000,
                'category' => 'Makanan',
                'is_available' => true,
            ],

            // ================== MINUMAN ==================
            [
                'name' => 'Es Kopi Susu Gula Aren',
                'description' => 'Perpaduan espresso, susu segar, dan manisnya gula aren.',
                'price' => 20000,
                'category' => 'Minuman',
                'is_available' => true,
            ],
            [
                'name' => 'Teh Tarik Jahe',
                'description' => 'Teh susu klasik dengan tambahan jahe hangat untuk melegakan tenggorokan.',
                'price' => 15000,
                'category' => 'Minuman',
                'is_available' => true,
            ],
            [
                'name' => 'Es Teh Leci',
                'description' => 'Es teh manis dengan tambahan buah leci dan sirupnya yang khas.',
                'price' => 12000,
                'category' => 'Minuman',
                'is_available' => false, // Contoh menu habis
            ],

            // ================== CEMILAN ==================
            [
                'name' => 'Cireng Bumbu Rujak',
                'description' => 'Cireng renyah di luar dan lembut di dalam, disajikan dengan saus rujak pedas manis.',
                'price' => 15000,
                'category' => 'Cemilan',
                'is_available' => true,
            ],
            [
                'name' => 'Pisang Goreng Keju Coklat',
                'description' => 'Pisang goreng crispy dengan taburan keju parut dan meses coklat melimpah.',
                'price' => 16000,
                'category' => 'Cemilan',
                'is_available' => true,
            ],
        ];

        // Looping untuk memasukkan semua data menu ke database
        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
