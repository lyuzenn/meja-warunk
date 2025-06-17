<div>
    {{-- Slot header untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pesanan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div wire:poll.5s="loadOrders" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($orders as $order)
                        {{-- PERBAIKAN: Menentukan class secara dinamis untuk menghindari cssConflict --}}
                        @php
                            $borderColorClass = match ($order->status) {
                                'paid' => 'border-blue-500',
                                'processing' => 'border-yellow-500',
                                default => 'border-gray-300',
                            };

                            $statusBadgeClasses = match ($order->status) {
                                'paid' => 'bg-blue-200 text-blue-800',
                                'processing' => 'bg-yellow-200 text-yellow-800',
                                'completed' => 'bg-green-200 text-green-800',
                                default => 'bg-gray-200 text-gray-800',
                            };
                        @endphp

                        <div wire:key="order-{{ $order->id }}" class="bg-gray-50 rounded-lg shadow-md p-4 border-l-4 {{ $borderColorClass }}">

                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-bold text-lg">Meja {{ $order->table->table_number }}</h3>
                                <span class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3">Pemesan: {{ $order->customer_name ?? 'N/A' }}</p>

                            <div class="border-t border-b border-gray-200 py-2 my-2 space-y-1">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $item->quantity }}x {{ $item->menu->name }}</span>
                                        <span class="text-gray-600">Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center font-bold mt-3">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="mt-4 pt-4 border-t">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $statusBadgeClasses }}">
                                    {{ $order->status }}
                                </span>

                                <div class="mt-3 flex gap-2">
                                    @if($order->status == 'paid')
                                        <button wire:click="updateOrderStatus({{ $order->id }}, 'processing')" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded">
                                            Proses
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
                            <p class="text-gray-500">Belum ada pesanan, nyantai dulu coy.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Elemen audio untuk notifikasi --}}
    {{-- <audio id="notification-sound" src="URL_SUARA_NOTIFIKASI_ANDA.mp3" preload="auto"></audio> --}}

    @push('scripts')
        <script>
            // Listener untuk memutar suara notifikasi
            window.addEventListener('play-notification-sound', event => {
                const sound = document.getElementById('notification-sound');
                if(sound) {
                    sound.play().catch(e => console.error("Audio play failed:", e));
                }
            });
        </script>
    @endpush
</div>
