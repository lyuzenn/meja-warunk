<div class="bg-gray-200">
    <!-- Container utama yang membatasi lebar seperti ponsel -->
    <div class="relative mx-auto max-w-sm min-h-screen bg-gray-100 shadow-2xl pb-28">

        <!-- Header -->
        <header class="bg-amber-900 text-white shadow-lg sticky top-0 z-40 p-3 flex items-center">
            <a href="{{ route('menu.show', ['table' => $tableId]) }}" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="font-bold text-xl tracking-wider text-center flex-grow">Rincian Pesanan</h1>
        </header>

        <!-- Konten Utama -->
        <main class="p-4">
            <!-- Kartu Utama yang Menggabungkan Semua Info -->
            <div class="bg-white rounded-lg shadow p-4 space-y-6">

                <!-- Bagian Daftar Pesanan -->
                <div>
                    <h2 class="font-bold text-lg mb-4">Pesanan Anda</h2>
                    <div class="space-y-4">
                        @forelse($cart as $id => $item)
                            <div class="flex items-center" wire:key="cart-item-{{ $id }}">
                                <div class="flex-grow pr-4">
                                    <p class="font-semibold">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center justify-between bg-gray-200 rounded-lg p-1 w-28">
                                    <button wire:click="decrementQuantity({{ $id }})" class="text-lg font-bold text-red-600 px-2 rounded-md">-</button>
                                    <span class="font-bold px-2 text-gray-800">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQuantity({{ $id }})" class="text-lg font-bold text-green-600 px-2 rounded-md">+</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Keranjang Anda kosong.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('menu.show', ['table' => $tableId]) }}" class="mt-4 inline-flex items-center text-amber-800 font-semibold hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" /></svg>
                        Tambah Item Lagi
                    </a>
                </div>

                <!-- Pembatas -->
                <hr>

                <!-- Bagian Nama & Catatan -->
                <div>
                     <div class="mb-4">
                        <label for="customerName" class="block text-sm font-medium text-gray-700">Nama Anda (Opsional)</label>
                        <input type="text" id="customerName" wire:model.defer="customerName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm" placeholder="Masukkan nama Anda">
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Pesanan (Opsional)</label>
                        <textarea id="notes" wire:model.defer="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm" placeholder="Contoh: Tidak pakai bawang, lebih pedas..."></textarea>
                    </div>
                </div>

                <!-- Bagian Rincian Pembayaran -->
                <div class="pt-4 border-t">
                    <h3 class="text-lg font-bold mb-3">Rincian Pembayaran</h3>
                    <div class="text-sm space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">PPN ({{ $taxRate * 100 }}%)</span>
                            <span class="font-medium">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                        </div>
                         <div class="flex justify-between text-lg font-bold text-amber-900 border-t pt-2 mt-2">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Bar Tombol Pesan (Floating) -->
    <footer class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-sm z-50 p-4">
        <button wire:click="processOrder" wire:loading.attr="disabled" class="w-full bg-amber-800 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-amber-900 transition-transform transform hover:scale-105 disabled:bg-gray-400">
            <span wire:loading.remove wire:target="processOrder">Buat Pesanan</span>
            <span wire:loading wire:target="processOrder">Memproses...</span>
        </button>
    </footer>
</div>
