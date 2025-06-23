<div class="bg-gray-200">
    <!-- Container utama yang membatasi lebar seperti ponsel -->
    <div class="relative mx-auto max-w-sm min-h-screen bg-gray-100 shadow-2xl">

        <!-- Konten Utama -->
        <main class="p-6 text-center">

            
            <!--[if BLOCK]><![endif]--><?php if($order->status === 'cancelled'): ?>
                <!-- Tampilan Pesanan Dibatalkan -->
                <div class="w-16 h-16 bg-red-100 rounded-full mx-auto flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mt-4">Pesanan Dibatalkan</h1>
                <p class="text-gray-600 mt-2">Mohon maaf, pesanan Anda telah dibatalkan. Silakan hubungi kasir untuk informasi lebih lanjut.</p>
            <?php else: ?>
                <!-- Tampilan Pesanan Berhasil -->
                <div class="w-16 h-16 bg-green-100 rounded-full mx-auto flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mt-4">Terima Kasih!</h1>
                <p class="text-gray-600 mt-2">Pesanan Anda telah kami terima dan sedang diproses.</p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Status Pesanan (Bisa Real-time) -->
            <div class="mt-8 text-left bg-white p-4 rounded-lg shadow">
                <h2 class="font-bold text-lg mb-4">Status Pesanan #<?php echo e($order->id); ?></h2>
                <div class="flex items-center">
                    <span class="font-semibold mr-2">Status Saat Ini:</span>
                    <span
                        class="text-sm font-bold uppercase px-3 py-1 rounded-full
                        <?php switch($order->status):
                            case ('paid'): ?> bg-blue-200 text-blue-800 <?php break; ?>
                            <?php case ('processing'): ?> bg-yellow-200 text-yellow-800 <?php break; ?>
                            <?php case ('completed'): ?> bg-green-200 text-green-800 <?php break; ?>
                            <?php case ('cancelled'): ?> bg-red-200 text-red-800 <?php break; ?>
                            <?php default: ?> bg-gray-200 text-gray-800
                        <?php endswitch; ?>">
                        <?php echo e($order->status); ?>

                    </span>
                </div>
            </div>

             <!-- Ringkasan Pesanan -->
            <div class="mt-4 text-left bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold mb-2">Ringkasan:</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex justify-between">
                            <span><?php echo e($item->quantity); ?>x <?php echo e($item->menu->name ?? 'Menu Dihapus'); ?></span>
                            <span>Rp <?php echo e(number_format($item->price_at_order * $item->quantity, 0, ',', '.')); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
                <hr class="my-2">
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span>Rp <?php echo e(number_format($order->total_price, 0, ',', '.')); ?></span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 space-y-4">
                <a href="<?php echo e(route('menu.show', ['table' => $order->table_id])); ?>" class="block w-full bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900">
                    Pesan Lagi
                </a>
                 <!--[if BLOCK]><![endif]--><?php if($order->status !== 'cancelled'): ?>
                 <a href="<?php echo e(route('order.invoice', $order->id)); ?>" class="block w-full bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-300">
                    Unduh Nota
                </a>
                 <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </main>
    </div>
</div>
<?php /**PATH C:\Users\Lyuzenn\laravel\meja-warunk\resources\views/livewire/order-status-page.blade.php ENDPATH**/ ?>