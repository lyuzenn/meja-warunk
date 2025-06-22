<div class="bg-gray-200">
    <!-- Container utama yang membatasi lebar seperti ponsel -->
    <div class="relative mx-auto max-w-sm min-h-screen bg-gray-100 shadow-2xl">

        <!-- Konten Utama -->
        <main class="p-6 text-center">
            <!-- Ikon Centang -->
            <div class="w-16 h-16 bg-green-100 rounded-full mx-auto flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <!-- Pesan Terima Kasih -->
            <h1 class="text-2xl font-bold text-gray-800 mt-4">Terima Kasih!</h1>
            <p class="text-gray-600 mt-2">Pesanan Anda telah kami terima dan sedang diproses.</p>

            <!-- Status Pesanan (Bisa Real-time) -->
            <div class="mt-8 text-left bg-white p-4 rounded-lg shadow">
                <h2 class="font-bold text-lg mb-4">Status Pesanan #{{ $order->id }}</h2>
                <div class="flex items-center">
                    <span class="font-semibold mr-2">Status Saat Ini:</span>
                    <span
                        wire:poll.5s="refreshOrder"
                        class="text-sm font-bold uppercase px-3 py-1 rounded-full
                        @switch($order->status)
                            @case('paid') bg-blue-200 text-blue-800 @break
                            @case('processing') bg-yellow-200 text-yellow-800 @break
                            @case('completed') bg-green-200 text-green-800 @break
                            @default bg-gray-200 text-gray-800
                        @endswitch">
                        {{ $order->status }}
                    </span>
                </div>
            </div>

             <!-- Ringkasan Pesanan -->
            <div class="mt-4 text-left bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold mb-2">Ringkasan:</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    @foreach($order->items as $item)
                        <li class="flex justify-between">
                            <span>{{ $item->quantity }}x {{ $item->menu->name }}</span>
                            <span>Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <hr class="my-2">
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 space-y-4">
                <a href="{{ route('menu.show', ['table' => $order->table_id]) }}" class="block w-full bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900">
                    Pesan Lagi
                </a>
                 <a href="{{ route('order.invoice', $order->id) }}" class="block w-full bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-300">
                    Unduh Nota
                </a>
            </div>
        </main>
    </div>
</div>
