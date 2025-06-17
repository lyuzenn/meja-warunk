<div class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        @if (session()->has('message'))
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Ulasan Terkirim!</h2>
                <p class="text-gray-600 mb-6">{{ session('message') }}</p>
                <a href="{{ route('order.invoice', $order->id) }}" target="_blank"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-lg inline-block">Unduh
                    Nota PDF</a>
            </div>
        @else
            <h2 class="text-2xl font-bold text-center mb-2">Beri Ulasan Anda</h2>
            <p class="text-center text-gray-500 mb-6">Pesanan #{{ $order->id }}</p>
            <div class="flex justify-center items-center mb-6">
                @for ($i = 1; $i <= 5; $i++)
                    <svg wire:click="setRating({{ $i }})"
                        class="w-10 h-10 cursor-pointer {{ $rating_value >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
            </div>
            @error('rating_value')
                <p class="text-red-500 text-sm text-center -mt-4 mb-4">{{ $message }}</p>
            @enderror
            <div class="mb-6">
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Komentar:</label>
                <textarea wire:model.defer="comment" id="comment" rows="4"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                    @if($hasRated) disabled @endif></textarea>
            </div>
            <button wire:click="submitRating" @if($hasRated) disabled @endif
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-lg transition duration-300 disabled:bg-gray-400">
                Kirim Ulasan
            </button>
        @endif
    </div>
</div>
