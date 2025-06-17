<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(Order $order)
    {
        $item_details = [];
        foreach ($order->items as $item) {
            $item_details[] = ['id' => $item->menu_id, 'price' => $item->price_at_order, 'quantity' => $item->quantity, 'name' => $item->menu->name];
        }
        $params = [
            'transaction_details' => [ 'order_id' => 'ORDER-' . $order->id . '-' . time(), 'gross_amount' => $order->total_price ],
            'item_details' => $item_details,
            'customer_details' => [ 'first_name' => $order->customer_name ?? 'Pelanggan', 'email' => 'customer' . $order->id . '@mejawarunk.com' ],
        ];
        $order->midtrans_order_id = $params['transaction_details']['order_id'];
        $order->save();
        return Snap::getSnapToken($params);
    }
}
