<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Menu Kami untuk Meja <?php echo e($table->table_number); ?></h1>

        <!-- Pesan notifikasi akan muncul di sini -->
        <div
            x-data="{ show: false, message: '' }"
            x-on:add-to-cart-notification.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            class="fixed top-5 right-5 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg"
            style="display: none;">
            <p x-text="message"></p>
        </div>

        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $menus->groupBy('category'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-12">
                <h2 class="text-2xl font-semibold border-b-2 border-yellow-400 pb-2 mb-6"><?php echo e($category); ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                            <img src="<?php echo e($menu->image ? asset('storage/' . $menu->image) : 'https://placehold.co/400x300/e2e8f0/e2e8f0?text=Food'); ?>" alt="<?php echo e($menu->name); ?>" class="w-full h-48 object-cover">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="font-bold text-xl mb-2"><?php echo e($menu->name); ?></h3>
                                <p class="text-gray-700 text-sm mb-4 flex-grow"><?php echo e($menu->description); ?></p>
                                <div class="flex justify-between items-center mt-auto">
                                    <span class="font-semibold text-lg">Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></span>
                                    <!--[if BLOCK]><![endif]--><?php if($menu->is_available): ?>
                                        <button wire:click="addToCart(<?php echo e($menu->id); ?>)" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                                            + Keranjang
                                        </button>
                                    <?php else: ?>
                                        <span class="text-sm font-semibold text-red-500 bg-red-100 px-3 py-1 rounded-full">Habis</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Tombol keranjang belanja yang melayang -->
        <!--[if BLOCK]><![endif]--><?php if(count($cart) > 0): ?>
        
        <button wire:click="checkout" class="fixed bottom-8 right-8 z-50 bg-blue-600 text-white rounded-full p-4 shadow-lg hover:bg-blue-700 transition duration-300 flex items-center justify-center">
            
            <div wire:loading wire:target="checkout">
                <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            <div wire:loading.remove wire:target="checkout">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center"><?php echo e(count($cart)); ?></span>
        </button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\Users\Lyuzenn\laravel\meja-warunk\resources\views/livewire/show-menu.blade.php ENDPATH**/ ?>