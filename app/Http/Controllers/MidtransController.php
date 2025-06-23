<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createPayment(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // REGENERATE MIDTRANS ORDER ID untuk mencegah duplikasi
        $newMidtransOrderId = 'MW-' . time() . '-' . $order->table_id . '-' . uniqid();
        $order->update(['midtrans_order_id' => $newMidtransOrderId]);

        Log::info('Midtrans Order ID regenerated:', [
            'order_id' => $order->id,
            'old_midtrans_id' => $order->getOriginal('midtrans_order_id'),
            'new_midtrans_id' => $newMidtransOrderId
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $newMidtransOrderId, // Gunakan ID yang baru
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => 'customer@example.com',
                'phone' => '08123456789',
            ],
            'item_details' => [
                [
                    'id' => 'ORDER-' . $order->id,
                    'price' => (int) $order->total_price,
                    'quantity' => 1,
                    'name' => 'Pesanan Meja ' . $order->table_id,
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            Log::info('Midtrans: Payment token generated successfully for Order ' . $order->id);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            Log::error('Request params:', $params);
            return response()->json(['error' => 'Payment gateway error: ' . $e->getMessage()], 500);
        }
    }

    public function notificationHandler(Request $request)
    {
        try {
            $notification = new Notification();

            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $order_id = $notification->order_id;
            $fraud = $notification->fraud_status;

            Log::info('Midtrans Notification Received: ', [
                'order_id' => $order_id,
                'transaction_status' => $transaction,
                'payment_type' => $type,
                'fraud_status' => $fraud
            ]);

            $order = Order::where('midtrans_order_id', $order_id)->first();

            if (!$order) {
                Log::error('Order not found for Midtrans order ID: ' . $order_id);
                return response('Order not found', 404);
            }

            Log::info('Order found, current status: ' . $order->status);

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->update(['status' => 'challenge']);
                        Log::info('Order status updated to: challenge');
                    } else {
                        $order->update(['status' => 'paid']);
                        Log::info('Order status updated to: paid');
                        // broadcast(new OrderPaid($order))->toOthers(); // Uncomment jika mau pakai broadcasting
                    }
                }
            } elseif ($transaction == 'settlement') {
                $order->update(['status' => 'paid']);
                Log::info('Order status updated to: paid (settlement)');
                // broadcast(new OrderPaid($order))->toOthers(); // Uncomment jika mau pakai broadcasting
            } elseif ($transaction == 'pending') {
                $order->update(['status' => 'pending']);
                Log::info('Order status remains: pending');
            } elseif ($transaction == 'deny') {
                $order->update(['status' => 'failed']);
                Log::info('Order status updated to: failed');
            } elseif ($transaction == 'expire') {
                $order->update(['status' => 'expired']);
                Log::info('Order status updated to: expired');
            } elseif ($transaction == 'cancel') {
                $order->update(['status' => 'cancelled']);
                Log::info('Order status updated to: cancelled');
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            Log::error('Request data: ', $request->all());
            return response('Error', 500);
        }
    }

    // Keep this for manual testing if needed
    public function simulatePayment(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $status = $request->input('status', 'paid');

        if ($status === 'paid') {
            $order->update(['status' => 'paid']);
            Log::info('✅ Order status updated to PAID - ID: ' . $order->id);
        } elseif ($status === 'pending') {
            $order->update(['status' => 'pending']);
            Log::info('⏳ Order status updated to PENDING - ID: ' . $order->id);
        } else {
            $order->update(['status' => 'failed']);
            Log::info('❌ Order status updated to FAILED - ID: ' . $order->id);
        }

        return response()->json([
            'message' => 'Status berubah menjadi ' . strtoupper($status),
            'order_id' => $order->id,
            'new_status' => $order->status
        ]);
    }
}
