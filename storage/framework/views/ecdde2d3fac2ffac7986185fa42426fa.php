<div>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Laporan Penjualan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Section -->
            <div class="bg-white p-4 rounded-lg shadow-sm flex items-center justify-between">
                <div>
                    <label for="period" class="text-sm font-medium text-gray-700">Pilih Periode:</label>
                    <select wire:model.live="period" id="period" class="ml-2 rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="today">Hari Ini</option>
                        <option value="last_7_days">7 Hari Terakhir</option>
                        <option value="last_30_days">30 Hari Terakhir</option>
                        <option value="this_month">Bulan Ini</option>
                    </select>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Total Pendapatan</h3><p class="mt-1 text-3xl font-semibold text-gray-900">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></p></div>
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Pesanan Berhasil</h3><p class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($totalOrders); ?></p></div>
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Item Terjual</h3><p class="mt-1 text-3xl font-semibold text-gray-900"><?php echo e($totalItemsSold); ?></p></div>
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Nilai Pesanan Rata-rata</h3><p class="mt-1 text-3xl font-semibold text-gray-900">Rp <?php echo e(number_format($averageOrderValue, 0, ',', '.')); ?></p></div>
            </div>

            <!-- Grafik Tren Penjualan -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold text-lg mb-4">Tren Penjualan</h3>
                <div wire:ignore style="height: 400px;">
                     <canvas id="salesReportChart"></canvas>
                </div>
            </div>

            <!-- Tabel Menu Terlaris -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold text-lg mb-4">Menu Terlaris</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Terjual</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $topSellingMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-900"><?php echo e(optional($item->menu)->name ?? 'Menu Dihapus'); ?></div><div class="text-sm text-gray-500"><?php echo e(optional($item->menu)->category ?? ''); ?></div></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"><?php echo e($item->total_quantity); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">Rp <?php echo e(number_format($item->total_revenue, 0, ',', '.')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data penjualan pada periode ini.</td></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            // PERBAIKAN: Menggunakan metode JSON parse yang lebih aman
            const initialData = JSON.parse('<?php echo json_encode($this->salesDataForChart); ?>');
            const ctx = document.getElementById('salesReportChart').getContext('2d');

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initialData.labels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: initialData.data,
                        backgroundColor: 'rgba(217, 119, 6, 0.2)',
                        borderColor: 'rgba(217, 119, 6, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true } },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            Livewire.on('updateChart', ({ data }) => {
                if (salesChart && data) {
                    salesChart.data.labels = data.labels;
                    salesChart.data.datasets[0].data = data.data;
                    salesChart.update();
                }
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
</div>
<?php /**PATH D:\Kuliah\Semester 4\PWEB\meja-warunk\resources\views/livewire/admin/sales-report.blade.php ENDPATH**/ ?>