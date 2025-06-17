<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Rating;

class DummyRatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Ambil hingga 150 pesanan yang sudah selesai dan belum memiliki rating
        $ordersToRate = Order::where('status', 'completed')
                               ->doesntHave('rating')
                               ->inRandomOrder()
                               ->limit(150)
                               ->get();

        if ($ordersToRate->isEmpty()) {
            $this->command->info('Tidak ada pesanan "completed" yang bisa diberi rating. Jalankan DummySalesSeeder terlebih dahulu.');
            return;
        }

        $positiveComments = [
            'Mantap, makanannya enak banget!',
            'Pelayanannya cepat dan ramah. Recommended!',
            'Sangat puas, pasti akan kembali lagi.',
            'Tempatnya nyaman dan bersih.',
            'Enak dan harganya terjangkau.',
        ];

        $negativeComments = [
            'Agak lama menunggunya, tapi rasanya lumayan.',
            'Minumannya kurang manis menurut saya.',
            'Parkirnya agak susah.',
            'Bisa lebih baik lagi.',
        ];

        foreach ($ordersToRate as $order) {
            // Tentukan rating bintang secara acak
            $ratingValue = rand(1, 5);

            $comment = null;
            // Beri komentar sekitar 80% dari waktu
            if (rand(1, 10) <= 8) {
                // Pilih komentar berdasarkan rating
                if ($ratingValue >= 4) {
                    $comment = $positiveComments[array_rand($positiveComments)];
                } else {
                    $comment = $negativeComments[array_rand($negativeComments)];
                }
            }

            Rating::create([
                'order_id' => $order->id,
                'rating_value' => $ratingValue,
                'comment' => $comment,
            ]);
        }

        $this->command->info($ordersToRate->count() . ' data rating dummy berhasil dibuat!');
    }
}
