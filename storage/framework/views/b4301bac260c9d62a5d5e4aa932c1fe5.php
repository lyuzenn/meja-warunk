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
            <?php elseif($order->status === 'pending'): ?>
                <!-- Tampilan Pesanan Menunggu Pembayaran -->
                <div class="w-16 h-16 bg-yellow-100 rounded-full mx-auto flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mt-4">Menunggu Pembayaran</h1>
                <p class="text-gray-600 mt-2">Pesanan Anda telah diterima. Silakan lakukan pembayaran untuk melanjutkan.</p>
            <?php elseif($order->status === 'paid'): ?>
                <!-- Tampilan Pesanan Sudah Dibayar -->
                <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto flex items-center justify-center">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mt-4">Pembayaran Berhasil!</h1>
                <p class="text-gray-600 mt-2">Pesanan Anda sudah dibayar dan sedang diproses oleh dapur.</p>
            <?php elseif($order->status === 'processing'): ?>
                <!-- Tampilan Pesanan Sedang Diproses -->
                <div class="w-16 h-16 bg-purple-100 rounded-full mx-auto flex items-center justify-center">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.334 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mt-4">Sedang Diproses</h1>
                <p class="text-gray-600 mt-2">Pesanan Anda sedang diproses oleh dapur. Mohon tunggu sebentar.</p>
            <?php elseif($order->status === 'completed'): ?>
                <!-- Tampilan Pesanan Selesai -->
                <div class="w-16 h-16 bg-green-100 rounded-full mx-auto flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mt-4">Pesanan Selesai!</h1>
                <p class="text-gray-600 mt-2">Pesanan Anda sudah selesai dan siap disajikan. Terima kasih!</p>
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
                            <?php case ('pending'): ?> bg-orange-200 text-orange-800 <?php break; ?>
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

                
                <!--[if BLOCK]><![endif]--><?php if($order->status === 'pending'): ?>
                    <button wire:click="goToPayment"
                            class="block w-full bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition-colors">
                        Bayar Sekarang
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($order->status === 'completed'): ?>
                    <a href="<?php echo e(route('menu.show', ['table' => $order->table_id])); ?>"
                       class="block w-full bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900 transition-colors">
                        Pesan Lagi
                    </a>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if(in_array($order->status, ['paid', 'processing', 'completed'])): ?>
                    <a href="<?php echo e(route('order.invoice', $order->id)); ?>"
                       class="block w-full bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors">
                        Unduh Nota
                    </a>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            </div>
        </main>
    </div>
</div>
<?php /**PATH D:\Kuliah\Semester 4\PWEB\meja-warunk\resources\views/livewire/order-status-page.blade.php ENDPATH**/ ?>