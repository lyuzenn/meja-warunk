<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Status Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="container mx-auto max-w-md p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            @if($order->status == 'paid')
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-gray-600">Pesanan Anda sedang kami proses.</p>

            @elseif($order->status == 'completed')
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Selesai!</h1>
                <p class="text-gray-600 mb-6">Terima kasih. Silakan berikan ulasan Anda.</p>
                <a href="{{ route('order.rate', $order->id) }}"
                   class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg text-lg inline-block">
                    Beri Ulasan & Dapatkan Nota
                </a>

            @else
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Menunggu Pembayaran</h1>
                <p class="text-gray-600">Silakan selesaikan pembayaran.</p>
            @endif

            <div class="mt-6 border-t pt-4 text-sm text-gray-500">
                <p>ID Pesanan: <span class="font-semibold">{{ $order->midtrans_order_id }}</span></p>
            </div>
        </div>
    </div>
</body>
</html>
