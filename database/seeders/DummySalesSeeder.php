<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Menu;
use Carbon\Carbon;

class DummySalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Pastikan ada meja dan menu terlebih dahulu
        // Jika belum ada, seeder ini akan membuatnya
        if (Table::count() == 0) {
            Table::create(['table_number' => '1']);
        }
        if (Menu::count() == 0) {
            Menu::create(['name' => 'Nasi Goreng', 'price' => 25000, 'category' => 'Makanan']);
            Menu::create(['name' => 'Mie Ayam', 'price' => 22000, 'category' => 'Makanan']);
            Menu::create(['name' => 'Es Teh Manis', 'price' => 5000, 'category' => 'Minuman']);
            Menu::create(['name' => 'Kopi Hitam', 'price' => 8000, 'category' => 'Minuman']);
        }

        $menus = Menu::all();
        $table = Table::first();

        // Loop untuk membuat data selama 30 hari terakhir
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Buat jumlah order acak untuk setiap hari (antara 5 sampai 20 order)
            $numberOfOrders = rand(5, 20);

            for ($j = 0; $j < $numberOfOrders; $j++) {
                $totalPrice = 0;
                $orderItems = [];

                // Buat jumlah item acak per order (antara 1 sampai 4 item)
                // PERBAIKAN: Diubah dari rand(1, 5) menjadi rand(1, 4) karena kita hanya punya 4 menu
                $numberOfItems = rand(1, 4);
                $selectedMenus = $menus->random($numberOfItems);

                foreach ($selectedMenus as $menu) {
                    $quantity = rand(1, 3);
                    $subtotal = $menu->price * $quantity;
                    $totalPrice += $subtotal;

                    $orderItems[] = [
                        'menu_id' => $menu->id,
                        'quantity' => $quantity,
                        'price_at_order' => $menu->price,
                    ];
                }

                // Buat order utama
                $order = Order::create([
                    'table_id' => $table->id,
                    'customer_name' => 'Pelanggan ' . rand(100, 999),
                    'total_price' => $totalPrice,
                    'status' => 'completed', // Anggap semua sudah selesai
                    'midtrans_order_id' => 'DUMMY-ORDER-' . uniqid(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Buat item-item order
                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }
            }
        }
    }
}
