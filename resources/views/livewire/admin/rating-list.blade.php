<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Ulasan Pelanggan') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="space-y-4">
                    @forelse ($ratings as $rating)
                        <div class="p-4 border rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $rating->rating_value >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="mt-2 text-gray-700">{{ $rating->comment ?: 'Tidak ada komentar.' }}</p>
                                </div>
                                <div class="text-right text-sm text-gray-500">
                                    <p>Pesanan #{{ $rating->order_id }}</p>
                                    <p>Meja {{ $rating->order->table->table_number ?? 'N/A' }}</p>
                                    <p>{{ $rating->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Belum ada ulasan.</p>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $ratings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
