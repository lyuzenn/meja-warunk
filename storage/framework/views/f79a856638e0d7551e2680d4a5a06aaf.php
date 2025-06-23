<div class="bg-gray-200">
    <!-- Container utama yang membatasi lebar seperti ponsel -->
    <div class="relative mx-auto max-w-sm min-h-screen bg-gray-100 shadow-2xl pb-28">

        <!-- PERUBAHAN: Header didesain ulang menjadi lebih elegan dan terpusat -->
        <header class="bg-amber-900 text-white shadow-lg sticky top-0 z-40 p-3 flex justify-center items-center">
            <h1 class="font-bold text-xl tracking-wider">Meja <?php echo e($table->table_number); ?></h1>
        </header>

        <!-- Info Lokasi dan Status Buka -->
        <div class="p-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-gray-800">MejaWarunk</h2>
                        <p class="text-sm text-gray-600">Sumbersari, Jember</p>
                    </div>
                    <div class="flex items-center text-sm text-green-600">
                        <span class="h-2 w-2 bg-green-500 rounded-full mr-2"></span>
                        Buka
                    </div>
                </div>
            </div>
        </div>


        <!-- PERUBAHAN: Menyesuaikan posisi sticky nav -->
        <nav class="bg-white sticky top-[52px] z-30 border-b">
            <div class="p-4 overflow-x-auto whitespace-nowrap">
                <button
                    wire:click="setActiveCategory('Semua')"
                    class="px-4 py-2 text-sm font-semibold rounded-full mr-2 transition-colors duration-300
                           <?php echo e($activeCategory === 'Semua' ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
                    Semua
                </button>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button
                        wire:click="setActiveCategory('<?php echo e($category); ?>')"
                        class="px-4 py-2 text-sm font-semibold rounded-full mr-2 transition-colors duration-300
                               <?php echo e($activeCategory === $category ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
                        <?php echo e($category); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </nav>

        <!-- Konten Menu -->
        <main class="p-4">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $menus->groupBy('category'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName => $menuItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-amber-900 mb-4"><?php echo e($categoryName); ?></h2>
                    <div class="grid grid-cols-2 gap-4">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div wire:key="menu-<?php echo e($menu->id); ?>" class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition-transform transform hover:-translate-y-1">
                                <img src="<?php echo e($menu->image ? asset('storage/' . $menu->image) : 'https://placehold.co/300x200/e2e8f0/e2e8f0?text=Food'); ?>" alt="<?php echo e($menu->name); ?>" class="w-full h-32 object-cover">
                                <div class="p-3 flex flex-col flex-grow">
                                    <h3 class="font-semibold text-sm text-gray-800 mb-1 truncate"><?php echo e($menu->name); ?></h3>
                                    <p class="text-gray-900 font-bold mb-2">Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></p>
                                    <div class="mt-auto">
                                        <!--[if BLOCK]><![endif]--><?php if(isset($cart[$menu->id])): ?>
                                            <div class="flex items-center justify-between bg-gray-100 rounded-lg p-1">
                                                <button wire:click="decrementQuantity(<?php echo e($menu->id); ?>)" class="text-lg font-bold text-red-600 px-2 rounded-md hover:bg-red-100">-</button>
                                                <span class="font-bold px-2 text-gray-800"><?php echo e($cart[$menu->id]['quantity']); ?></span>
                                                <button wire:click="incrementQuantity(<?php echo e($menu->id); ?>)" class="text-lg font-bold text-green-600 px-2 rounded-md hover:bg-green-100">+</button>
                                            </div>
                                        <?php else: ?>
                                            <button wire:click="addToCart(<?php echo e($menu->id); ?>)" class="w-full bg-white border border-amber-800 text-amber-800 font-bold py-2 px-4 rounded-lg hover:bg-amber-800 hover:text-white transition">
                                                Add
                                            </button>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-500">Tidak ada menu yang tersedia.</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </main>

    </div>

    <!-- Bar Checkout Bawah -->
    <!--[if BLOCK]><![endif]--><?php if(count($cart) > 0): ?>
        <footer class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-sm bg-white shadow-[0_-2px_10px_rgba(0,0,0,0.1)] border-t z-50">
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="bg-amber-800 text-white rounded-lg p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <span class="absolute -top-2 -right-2 bg-amber-200 text-amber-900 text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center border-2 border-white"><?php echo e($totalItems); ?></span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="font-bold text-lg text-gray-900">Rp <?php echo e(number_format($totalPrice, 0, ',', '.')); ?></p>
                        </div>
                    </div>
                    <button wire:click="checkout" class="bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900 transition-transform transform hover:scale-105">
                        CHECK OUT
                    </button>
                </div>
            </div>
        </footer>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Notifikasi "Ditambahkan ke keranjang" -->
    <div
        x-data="{ show: false, message: '' }"
        x-on:add-to-cart-notification.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 2000)"
        x-show="show"
        x-transition
        class="fixed top-20 left-1/2 -translate-x-1/2 bg-black bg-opacity-70 text-white px-4 py-2 rounded-lg shadow-lg z-50"
        style="display: none;">
        <p x-text="message"></p>
    </div>
</div>
<?php /**PATH D:\Kuliah\Semester 4\PWEB\meja-warunk\resources\views/livewire/show-menu.blade.php ENDPATH**/ ?>