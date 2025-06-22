<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pesanan #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1a1a1a;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .order-details, .item-details {
            width: 100%;
            margin-bottom: 20px;
        }
        .order-details th, .order-details td,
        .item-details th, .item-details td {
            padding: 5px;
            text-align: left;
        }
        .item-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-details th, .item-details td {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }
        .item-details th {
            font-weight: bold;
            text-align: left;
            padding-bottom: 10px;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        .totals td {
            padding: 5px 0;
        }
        .totals .label {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MejaWarunk</h1>
            <p>Sumbersari, Jember</p>
            <p>Terima kasih atas pesanan Anda!</p>
        </div>

        <table class="order-details">
            <tr>
                <th>No. Pesanan:</th>
                <td>#{{ $order->id }}</td>
            </tr>
            <tr>
                <th>Tanggal:</th>
                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
            </tr>
             <tr>
                <th>Meja:</th>
                <td>{{ $order->table->table_number }}</td>
            </tr>
            @if($order->customer_name)
            <tr>
                <th>Nama Pelanggan:</th>
                <td>{{ $order->customer_name }}</td>
            </tr>
            @endif
             @if($order->notes)
            <tr>
                <th>Catatan:</th>
                <td>{{ $order->notes }}</td>
            </tr>
            @endif
        </table>

        <div class="item-details">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                    @endphp
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->menu->name }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $subtotal += $item->price_at_order * $item->quantity;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <table class="totals">
            <tr>
                <td class="label">Subtotal</td>
                <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
             <tr>
                <td class="label">PPN (11%)</td>
                <td class="text-right">Rp {{ number_format($order->total_price - $subtotal, 0, ',', '.') }}</td>
            </tr>
             <tr>
                <td class="label" style="font-size: 14px; border-top: 1px solid #333; padding-top: 10px;">Total</td>
                <td class="text-right" style="font-size: 14px; font-weight: bold; border-top: 1px solid #333; padding-top: 10px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Satu titik dua koma.</p>
            <p>Jangan lupa datang lagi yaa :D.</p>
        </div>
    </div>
</body>
</html>
