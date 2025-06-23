<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERBAIKAN: Hanya hapus data transaksional (pesanan dan ulasan)
        // Ini akan menjaga data menu, meja, dan user Anda tetap aman.
        if (DB::getDriverName() !== 'sqlite') {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        Rating::truncate();
        Order::truncate();

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('Data pesanan dan ulasan lama telah dihapus.');

        // Ambil semua menu dan meja yang tersedia
        $menus = Menu::where('is_available', true)->get();
        $tables = Table::all();

        if ($menus->isEmpty() || $tables->isEmpty()) {
            $this->command->error('Tidak dapat membuat data dummy. Pastikan ada data di tabel "menus" dan "tables".');
            return;
        }

        // Inisialisasi Faker dengan lokal Bahasa Indonesia
        $faker = \Faker\Factory::create('id_ID');

        // Kumpulan contoh catatan pesanan dalam Bahasa Indonesia
        $sampleNotes = [
            'Sambalnya tolong dipisah ya.',
            'Jangan terlalu pedas, buat anak-anak.',
            'Tolong ekstra bawang goreng.',
            'Tidak pakai sayur, terima kasih.',
            'Es batunya sedikit saja.',
        ];

        // PERBAIKAN: Kumpulan contoh ulasan dalam Bahasa Indonesia
        $sampleComments = [
            'Rasa makanannya enak banget, bumbunya pas. Pelayanannya juga cepat dan ramah. Pasti bakal balik lagi ke sini!',
            'Tempatnya nyaman buat nongkrong bareng teman-teman. Kopi susunya juara!',
            'Nasi Bakarnya wangi dan isinya banyak. Sangat direkomendasikan.',
            'Harganya terjangkau untuk kantong mahasiswa. Cirengnya favorit saya.',
            'Mantap! Semua pesanan sesuai dan rasanya tidak pernah mengecewakan. Pertahankan kualitasnya.',
            'Sedikit saran, mungkin bisa ditambah variasi menu cemilan. Tapi secara keseluruhan sudah oke.',
            'Suka banget sama suasananya, adem dan bersih. Teh tariknya juga enak.',
        ];

        $this->command->info('Membuat 110 data pesanan dan ~70 ulasan dummy...');

        // Membuat 110 pesanan acak
        for ($i = 0; $i < 110; $i++) {
            $orderDate = Carbon::now()->subDays(rand(0, 30));
            $orderStatus = ['paid', 'processing', 'completed'][rand(0, 2)];

            // Buat pesanan utama
            $order = Order::create([
                'table_id' => $tables->random()->id,
                'customer_name' => $faker->firstName() . ' ' . $faker->lastName(),
                'total_price' => 0, // Akan di-update nanti
                'status' => $orderStatus,
                'notes' => $faker->boolean(30) ? $faker->randomElement($sampleNotes) : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            $subtotal = 0;
            $itemCount = rand(1, 5); // Jumlah item bervariasi

            // Tambahkan item-item acak ke pesanan
            for ($j = 0; $j < $itemCount; $j++) {
                $menu = $menus->random();
                $quantity = rand(1, 3);

                $order->items()->create([
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'price_at_order' => $menu->price,
                ]);

                $subtotal += $menu->price * $quantity;
            }

            // Update total harga di pesanan dengan asumsi PPN 11%
            $order->total_price = $subtotal * 1.11;
            $order->save();

            // Buat rating untuk ~65% pesanan yang sudah 'completed'
            if ($order->status === 'completed' && $faker->boolean(65)) {
                Rating::create([
                    'order_id' => $order->id,
                    'rating_value' => rand(3, 5),
                    // PERBAIKAN: Ambil komentar acak dari kumpulan yang sudah kita siapkan
                    'comment' => $faker->boolean(75) ? $faker->randomElement($sampleComments) : null,
                    'created_at' => $orderDate->addHours(rand(1, 5)),
                    'updated_at' => $orderDate->addHours(rand(1, 5)),
                ]);
            }
        }

        $this->command->info('Dummy data berhasil dibuat.');
    }
}
