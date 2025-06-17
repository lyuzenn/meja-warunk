


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - MejaWarunk</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="container mx-auto max-w-md p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!--[if BLOCK]><![endif]--><?php if($order->status == 'paid'): ?>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Diterima!</h1>
                <p class="text-gray-600">Pesanan Anda sedang kami siapkan. Mohon ditunggu.</p>
            <?php elseif($order->status == 'processing'): ?>
                 <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Diproses</h1>
                <p class="text-gray-600">Harap sabar, pesanan Anda sedang dibuat oleh koki terbaik kami!</p>
            <?php elseif($order->status == 'completed'): ?>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Selesai!</h1>
                <p class="text-gray-600 mb-6">Terima kasih telah memesan. Silakan berikan ulasan Anda untuk membantu kami.</p>
                <a href="<?php echo e(route('order.rate', $order->id)); ?>" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg text-lg inline-block">
                    Beri Ulasan & Dapatkan Nota
                </a>
            <?php else: ?> 
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Dibatalkan</h1>
                <p class="text-gray-600">Terjadi masalah dengan pesanan Anda.</p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <div class="mt-6 border-t pt-4 text-sm text-gray-500">
                <p>ID Pesanan Anda: <span class="font-semibold"><?php echo e($order->midtrans_order_id); ?></span></p>
            </div>
        </div>
    </div>
</body>
</html>

<?php /**PATH C:\Users\Lyuzenn\laravel\meja-warunk\resources\views/livewire/order-status-page.blade.php ENDPATH**/ ?>