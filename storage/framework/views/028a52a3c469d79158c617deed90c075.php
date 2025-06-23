<div>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Manajemen Menu')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">

                <!-- Tombol untuk membuka modal tambah menu -->
                <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                    Tambah Menu Baru
                </button>

                <!-- Menampilkan pesan sukses setelah operasi -->
                <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm"><?php echo e(session('message')); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Modal untuk form tambah/edit menu -->
                <!--[if BLOCK]><![endif]--><?php if($isModalOpen): ?>
                    <?php echo $__env->make('livewire.admin.menu-form-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Tabel untuk menampilkan semua menu -->
                <table class="table-fixed w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 w-20">No.</th>
                            <th class="px-4 py-2">Gambar</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Harga</th>
                            <th class="px-4 py-2">Kategori</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="border px-4 py-2 text-center"><?php echo e($index + 1); ?></td>
                            <td class="border px-4 py-2 text-center">
                                <img src="<?php echo e($menu->image ? asset('storage/' . $menu->image) : 'https://placehold.co/100x100/e2e8f0/e2e8f0?text=Food'); ?>" alt="<?php echo e($menu->name); ?>" class="h-16 w-16 object-cover rounded-md mx-auto">
                            </td>
                            <td class="border px-4 py-2"><?php echo e($menu->name); ?></td>
                            <td class="border px-4 py-2">Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></td>
                            <td class="border px-4 py-2"><?php echo e($menu->category); ?></td>
                            <td class="border px-4 py-2 text-center">
                                <span class="<?php echo e($menu->is_available ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'); ?> text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    <?php echo e($menu->is_available ? 'Tersedia' : 'Habis'); ?>

                                </span>
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <button wire:click="edit(<?php echo e($menu->id); ?>)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                <button wire:click="delete(<?php echo e($menu->id); ?>)" wire:confirm="Anda yakin ingin menghapus menu ini?" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="border px-4 py-2 text-center" colspan="7">Belum ada data menu.</td>
                        </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\Kuliah\Semester 4\PWEB\meja-warunk\resources\views/livewire/admin/menu-manager.blade.php ENDPATH**/ ?>