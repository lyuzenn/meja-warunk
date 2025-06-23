<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran - Meja Warunk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .pay-button { background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; margin: 10px 0; }
        .pay-button:hover { background: #218838; }
        .pay-button:disabled { background: #6c757d; cursor: not-allowed; }
        .order-details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .sandbox-badge { background: #17a2b8; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="sandbox-badge">🏖️ MIDTRANS SANDBOX</div>
        <h2>💳 Pembayaran Pesanan</h2>

        <div class="order-details">
            <p><strong>Order ID:</strong> {{ $order->midtrans_order_id }}</p>
            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
            <p><strong>Meja:</strong> {{ $order->table_id }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</p>
            <p><strong>Pajak:</strong> Rp {{ number_format($order->tax_amount ?? 0, 0, ',', '.') }}</p>
            <p><strong>Total:</strong> <span style="font-size: 18px; color: #28a745;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
        </div>

        <button id="pay-button" class="pay-button">Bayar Sekarang dengan Midtrans</button>

        <p style="font-size: 14px; color: #666; text-align: center; margin-top: 20px;">
            Status akan otomatis terupdate setelah pembayaran berhasil
        </p>
    </div>

    <script>
        document.getElementById('pay-button').onclick = function() {
            // Disable button untuk prevent double click
            this.disabled = true;
            this.innerText = 'Memproses pembayaran...';

            console.log('Creating payment for order ID:', {{ $order->id }});

            fetch('/payment/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    order_id: {{ $order->id }}
                })
            })
            .then(response => {
                console.log('Payment create response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Payment create response data:', data);

                if (data.snap_token) {
                    console.log('Opening Midtrans popup with token:', data.snap_token);

                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('🎉 Payment Success:', result);

                            // OTOMATIS UPDATE STATUS SETELAH PEMBAYARAN BERHASIL
                            console.log('Updating order status to paid...');

                            fetch('/payment/simulate', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    order_id: {{ $order->id }},
                                    status: 'paid'
                                })
                            })
                            .then(response => response.json())
                            .then(updateData => {
                                console.log('✅ Status update response:', updateData);
                                alert('🎉 Pembayaran berhasil!\n\nStatus: ' + updateData.message);
                                window.location.href = '/order/{{ $order->id }}/status';
                            })
                            .catch(error => {
                                console.error('❌ Error updating status:', error);
                                alert('✅ Pembayaran berhasil!\n⚠️ Tapi status tidak terupdate otomatis.\n\nSilakan refresh halaman status.');
                                window.location.href = '/order/{{ $order->id }}/status';
                            });
                        },

                        onPending: function(result) {
                            console.log('⏳ Payment Pending:', result);
                            alert('⏳ Pembayaran tertunda\n\nSilakan selesaikan pembayaran Anda.');
                            window.location.href = '/order/{{ $order->id }}/status';
                        },

                        onError: function(result) {
                            console.log('❌ Payment Error:', result);
                            alert('❌ Pembayaran gagal!\n\nSilakan coba lagi.');
                            // Enable button kembali
                            document.getElementById('pay-button').disabled = false;
                            document.getElementById('pay-button').innerText = 'Bayar Sekarang dengan Midtrans';
                        },

                        onClose: function() {
                            console.log('👆 Payment popup closed by user');
                            alert('Anda menutup popup pembayaran.\n\nSilakan klik tombol bayar lagi jika ingin melanjutkan.');
                            // Enable button kembali
                            document.getElementById('pay-button').disabled = false;
                            document.getElementById('pay-button').innerText = 'Bayar Sekarang dengan Midtrans';
                        }
                    });
                } else {
                    console.error('❌ No snap token received:', data);
                    alert('❌ Error: ' + (data.error || 'Gagal membuat pembayaran'));
                    // Enable button kembali
                    document.getElementById('pay-button').disabled = false;
                    document.getElementById('pay-button').innerText = 'Bayar Sekarang dengan Midtrans';
                }
            })
            .catch(error => {
                console.error('❌ Fetch error:', error);
                alert('❌ Terjadi kesalahan saat memproses pembayaran!\n\nError: ' + error.message);
                // Enable button kembali
                document.getElementById('pay-button').disabled = false;
                document.getElementById('pay-button').innerText = 'Bayar Sekarang dengan Midtrans';
            });
        };
    </script>
</body>
</html>
