<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Menu; 
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        $table_id = session('table_id');

        if (empty($cart) || !$table_id) {
            return redirect()->route('menu.show', ['table' => $table_id ?? 1])
                             ->with('error', 'Keranjang Anda kosong atau sesi meja tidak ditemukan!');
        }

        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('checkout', compact('cart', 'totalPrice', 'table_id'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);
        $table_id = session('table_id');

        if (empty($cart) || !$table_id) {
            return response()->json(['error' => 'Sesi Anda telah berakhir. Silakan ulangi pesanan.'], 400);
        }

        // --- PERBAIKAN UTAMA: Validasi Keranjang Belanja ---
        $menuIdsInCart = array_keys($cart);
        $existingMenusCount = Menu::whereIn('id', $menuIdsInCart)->count();

        // Jika jumlah item di keranjang tidak sama dengan jumlah item yang ada di database...
        if (count($menuIdsInCart) !== $existingMenusCount) {
            // Kosongkan keranjang yang tidak valid untuk memaksa pengguna memilih ulang.
            session()->forget('cart');
            return response()->json([
                'error' => 'Beberapa item di keranjang Anda sudah tidak tersedia. Keranjang telah dikosongkan, silakan kembali ke menu dan ulangi pesanan Anda.'
            ], 400); // 400 Bad Request
        }

        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'table_id' => $table_id,
                'customer_name' => $request->input('customer_name', 'Pelanggan'),
                'total_price' => $totalPrice,
                'status' => 'paid',
                'midtrans_order_id' => 'OFFLINE-ORDER-' . uniqid(),
            ]);

            foreach ($cart as $menuId => $item) {
                $order->items()->create([
                    'menu_id' => $menuId,
                    'quantity' => $item['quantity'],
                    'price_at_order' => $item['price'],
                ]);
            }

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('DATABASE ERROR on Checkout: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan pada database, silakan coba lagi.'], 500);
        }

        try {
            broadcast(new OrderPaid($order))->toOthers();
            Log::info('Successfully broadcasted OrderPaid event for Order ID: ' . $order->id);
        } catch (Throwable $e) {
            Log::error('BROADCASTING FAILED for Order ID: ' . $order->id . ' - ' . $e->getMessage());
        }

        session()->forget(['cart', 'table_id']);

        return response()->json([
            'message' => 'Pesanan berhasil dibuat!',
            'redirect_url' => route('order.finish', $order->id)
        ]);
    }

    public function finish(Order $order)
    {
        return view('order-finish', compact('order'));
    }
}
