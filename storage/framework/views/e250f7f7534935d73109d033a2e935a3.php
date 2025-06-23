
<div x-data="{
    // State untuk notifikasi pesanan baru
    showOrderToast: false,
    orderToastMessage: '',

    // State untuk notifikasi status suara
    showSoundToast: false,
    soundToastMessage: '',

    // State untuk saklar suara, disimpan di localStorage browser
    soundEnabled: localStorage.getItem('soundEnabled') === 'true',

    // Fungsi yang dipanggil saat saklar suara diubah
    toggleSound() {
        // Nilai soundEnabled sudah diubah oleh x-model, fungsi ini untuk efek tambahan
        localStorage.setItem('soundEnabled', this.soundEnabled);

        this.soundToastMessage = this.soundEnabled ? 'Notifikasi suara diaktifkan.' : 'Notifikasi suara dinonaktifkan.';
        this.showSoundToast = true;
        setTimeout(() => this.showSoundToast = false, 3000);

        if (this.soundEnabled) {
            // Coba putar suara sekali untuk 'meminta izin' ke browser
            this.$nextTick(() => {
                document.getElementById('notification-sound')?.play().catch(e => console.warn('Browser memblokir pemutaran awal.'));
            });
        }
    }
}" x-on:new-order-notification.window="
    // PERBAIKAN: Mengambil data dari $event.detail[0]
    orderToastMessage = $event.detail[0].message;
    showOrderToast = true;
    if (soundEnabled) {
        $nextTick(() => {
            document.getElementById('notification-sound')?.play().catch(e => console.error('Gagal memutar notifikasi:', e));
        });
    }
    setTimeout(() => showOrderToast = false, 7000);
">

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Dashboard Pesanan Masuk')); ?>

            </h2>

            <!-- Toggle Switch yang lebih baik dan terintegrasi -->
            <label for="sound-toggle" class="flex items-center cursor-pointer">
                <span class="mr-3 text-sm font-medium text-gray-700">Suara Notifikasi</span>
                <div class="relative">
                    <input type="checkbox" id="sound-toggle" class="sr-only" x-model="soundEnabled" @change="toggleSound">
                    <div class="block bg-gray-300 w-11 h-6 rounded-full transition-colors" :class="{ 'bg-green-500': soundEnabled }"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="{ 'translate-x-full': soundEnabled }"></div>
                </div>
            </label>
        </div>
     <?php $__env->endSlot(); ?>

    <!-- Toast untuk Notifikasi Pesanan Baru -->
    <div
        x-show="showOrderToast"
        x-transition
        class="fixed top-24 right-5 bg-green-600 text-white py-3 px-5 rounded-lg shadow-lg z-50"
        style="display: none;">
        <p x-text="orderToastMessage"></p>
    </div>

    <!-- Toast untuk Status Suara -->
    <div
        x-show="showSoundToast"
        x-transition
        class="fixed top-40 right-5 bg-blue-500 text-white py-3 px-5 rounded-lg shadow-lg z-50"
        style="display: none;">
        <p x-text="soundToastMessage"></p>
    </div>

    <!-- Elemen Audio untuk Suara Notifikasi -->
    <audio id="notification-sound" src="<?php echo e(Vite::asset('resources/sounds/notification.mp3')); ?>" preload="auto"></audio>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $borderColorClass = match ($order->status) {
                                'paid' => 'border-blue-500',
                                'processing' => 'border-yellow-500',
                                'cancelled' => 'border-red-500',
                                default => 'border-gray-300',
                            };
                        ?>

                        <div wire:key="order-<?php echo e($order->id); ?>" class="bg-gray-50 rounded-lg shadow-md p-4 border-l-4 <?php echo e($borderColorClass); ?>">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-bold text-lg">Meja <?php echo e($order->table->table_number); ?></h3>
                                <span class="text-sm text-gray-500"><?php echo e($order->created_at->diffForHumans()); ?></span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3">Pemesan: <?php echo e($order->customer_name ?? 'N/A'); ?></p>

                            <!--[if BLOCK]><![endif]--><?php if($order->notes): ?>
                                <div class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm text-amber-800"><?php echo e($order->notes); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <div class="border-t border-b border-gray-200 py-2 my-2 space-y-1">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex justify-between text-sm">
                                        <span><?php echo e($item->quantity); ?>x <?php echo e($item->menu->name ?? 'Menu Dihapus'); ?></span>
                                        <span class="text-gray-600">Rp <?php echo e(number_format($item->price_at_order * $item->quantity, 0, ',', '.')); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="flex justify-between items-center font-bold mt-3">
                                <span>Total</span>
                                <span>Rp <?php echo e(number_format($order->total_price, 0, ',', '.')); ?></span>
                            </div>

                            <?php
                                $statusBadgeClasses = match ($order->status) {
                                    'paid' => 'bg-blue-200 text-blue-800',
                                    'processing' => 'bg-yellow-200 text-yellow-800',
                                    'completed' => 'bg-green-200 text-green-800',
                                    'cancelled' => 'bg-red-200 text-red-800',
                                    default => 'bg-gray-200 text-gray-800',
                                };
                            ?>

                            <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full <?php echo e($statusBadgeClasses); ?>">
                                    <?php echo e($order->status); ?>

                                </span>

                                <div class="flex gap-2">
                                    <!--[if BLOCK]><![endif]--><?php if($order->status == 'paid'): ?>
                                        <button wire:click="updateOrderStatus(<?php echo e($order->id); ?>, 'processing')" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded">
                                            Proses
                                        </button>
                                        <button wire:click="updateOrderStatus(<?php echo e($order->id); ?>, 'cancelled')" wire:confirm="Anda yakin ingin membatalkan pesanan ini?" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-1 px-3 rounded">
                                            Batal
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                    <!--[if BLOCK]><![endif]--><?php if($order->status == 'processing'): ?>
                                        <button wire:click="updateOrderStatus(<?php echo e($order->id); ?>, 'completed')" class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-1 px-3 rounded">
                                            Selesaikan
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                            <p class="text-gray-500">Belum ada pesanan yang masuk hari ini.</p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Lyuzenn\laravel\meja-warunk\resources\views/livewire/admin/order-dashboard.blade.php ENDPATH**/ ?>