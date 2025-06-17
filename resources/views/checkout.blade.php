{{-- File: resources/views/checkout.blade.php --}}
{{-- Ganti seluruh isi file ini dengan kode di bawah. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - MejaWarunk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- PERUBAHAN: Script Midtrans tidak diperlukan lagi --}}
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-lg p-4 my-10">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-center mb-6">Ringkasan Pesanan</h1>

            <!-- Order Items -->
            <div class="mb-6">
                @foreach($cart as $item)
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-semibold">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    <p class="font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="flex justify-between font-bold text-xl mb-6">
                <span>Total</span>
                <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

             <!-- Customer Name -->
            <div class="mb-6">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Anda (Opsional)</label>
                <input type="text" id="customer_name" name="customer_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan nama Anda">
            </div>

            {{-- PERUBAHAN: Nama tombol diubah menjadi lebih sesuai --}}
            <button id="order-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-lg transition duration-300">
                Buat Pesanan
            </button>
        </div>
    </div>

    {{-- PERUBAHAN: Logika JavaScript diubah total --}}
    <script type="text/javascript">
        var orderButton = document.getElementById('order-button');
        orderButton.addEventListener('click', function () {
            orderButton.disabled = true;
            orderButton.innerText = 'Memproses...';

            fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    customer_name: document.getElementById('customer_name').value
                })
            })
            .then(response => response.json())
            .then(data => {
                // Cek jika ada URL redirect dari server
                if (data.redirect_url) {
                    // Langsung arahkan ke halaman status pesanan
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + data.error);
                    orderButton.disabled = false;
                    orderButton.innerText = 'Buat Pesanan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                orderButton.disabled = false;
                orderButton.innerText = 'Buat Pesanan';
            });
        });
    </script>
</body>
</html>
