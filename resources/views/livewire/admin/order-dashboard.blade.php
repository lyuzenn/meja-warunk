<div x-data="{
    soundEnabled: localStorage.getItem('soundEnabled') === 'true',
    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        localStorage.setItem('soundEnabled', this.soundEnabled);
        if (this.soundEnabled) {
            // Coba putar suara sekali untuk mendapatkan izin dari browser
            document.getElementById('notification-sound').play().catch(e => {
                console.error('Gagal memutar suara, perlu interaksi pengguna.', e);
            });
        }
    }
}">
    {{-- Slot header untuk judul halaman --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Pesanan Masuk') }}
            </h2>

            <div class="flex items-center space-x-6">
                <button onclick="playTestSound()" class="text-sm font-medium text-blue-600 hover:underline">
                    Tes Suara
                </button>

                <button @click="toggleSound()" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg x-show="soundEnabled" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707a1 1 0 011.414 0v14.142a1 1 0 01-1.414 0L5.586 15z" /></svg>
                    <svg x-show="!soundEnabled" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707a1 1 0 011.414 0v14.142a1 1 0 01-1.414 0L5.586 15zM17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" /></svg>
                    <span x-text="soundEnabled ? 'Suara Aktif' : 'Suara Mati'"></span>
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Toast Notifikasi (menggunakan Alpine.js) -->
    <div
        x-data="{ show: false, message: '' }"
        x-on:new-order-notification.window="
            show = true;
            message = $event.detail.message;
            if (localStorage.getItem('soundEnabled') === 'true') {
                document.getElementById('notification-sound').play().catch(e => console.error('Gagal memutar notifikasi otomatis:', e));
            }
            setTimeout(() => show = false, 7000)
        "
        x-show="show"
        x-transition
        class="fixed top-24 right-5 bg-green-600 text-white py-3 px-5 rounded-lg shadow-lg z-50"
        style="display: none;">
        <p x-text="message"></p>
    </div>

    <!-- Elemen Audio untuk Suara Notifikasi -->
    <audio id="notification-sound" src="{{ Vite::asset('resources/sounds/notification.mp3') }}" preload="auto"></audio>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div wire:poll.5s="loadOrders" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($orders as $order)
                        @php
                            $borderColorClass = match ($order->status) {
                                'paid' => 'border-blue-500',
                                'processing' => 'border-yellow-500',
                                'cancelled' => 'border-red-500',
                                default => 'border-gray-300',
                            };
                        @endphp

                        <div wire:key="order-{{ $order->id }}" class="bg-gray-50 rounded-lg shadow-md p-4 border-l-4 {{ $borderColorClass }}">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-bold text-lg">Meja {{ $order->table->table_number }}</h3>
                                <span class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3">Pemesan: {{ $order->customer_name ?? 'N/A' }}</p>

                            @if($order->notes)
                                <div class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm text-amber-800">{{ $order->notes }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="border-t border-b border-gray-200 py-2 my-2 space-y-1">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $item->quantity }}x {{ $item->menu->name ?? 'Menu Dihapus' }}</span>
                                        <span class="text-gray-600">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center font-bold mt-3">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            @php
                                $statusBadgeClasses = match ($order->status) {
                                    'paid' => 'bg-blue-200 text-blue-800',
                                    'processing' => 'bg-yellow-200 text-yellow-800',
                                    'completed' => 'bg-green-200 text-green-800',
                                    'cancelled' => 'bg-red-200 text-red-800',
                                    default => 'bg-gray-200 text-gray-800',
                                };
                            @endphp

                            <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $statusBadgeClasses }}">
                                    {{ $order->status }}
                                </span>

                                <div class="flex gap-2">
                                    @if($order->status == 'paid')
                                        <button wire:click="updateOrderStatus({{ $order->id }}, 'processing')" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded">
                                            Proses
                                        </button>
                                        <button wire:click="updateOrderStatus({{ $order->id }}, 'cancelled')" wire:confirm="Anda yakin ingin membatalkan pesanan ini?" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-1 px-3 rounded">
                                            Batal
                                        </button>
                                    @endif

                                    @if($order->status == 'processing')
                                        <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-1 px-3 rounded">
                                            Selesaikan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                            <p class="text-gray-500">Belum ada pesanan yang masuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Mendefinisikan fungsi tes suara di scope global agar bisa dipanggil oleh onclick
        function playTestSound() {
            console.log('Tombol Tes Suara diklik!');
            const audio = document.getElementById('notification-sound');

            if (audio) {
                console.log('Elemen audio ditemukan. Mencoba memutar dari:', audio.src);
                audio.play()
                    .then(() => {
                        console.log('Suara berhasil diputar.');
                    })
                    .catch(e => {
                        console.error('Error saat memutar suara:', e);
                        alert('Browser memblokir pemutaran suara. Coba aktifkan saklar suara, lalu coba tes lagi.');
                    });
            } else {
                console.error('Elemen audio dengan ID "notification-sound" tidak ditemukan!');
            }
        }
    </script>
    @endpush
</div>
