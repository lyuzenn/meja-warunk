<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckoutPage extends Component
{
    public $cart = [];
    public $tableId;
    public $customerName;
    public $notes;

    public $subtotal = 0;
    public $taxAmount = 0;
    public $totalPrice = 0;
    public $taxRate = 0.11;

    public function mount()
    {
        $this->cart = session('cart', []);
        $this->tableId = session('table_id');

        if (empty($this->cart) || !$this->tableId) {
            return redirect()->route('menu.show', ['table' => $this->tableId ?? 1]);
        }

        $this->calculateTotals();
    }

    public function render()
    {
        return view('livewire.checkout-page')->layout('layouts.guest');
    }

    public function incrementQuantity($menuId)
    {
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
            $this->updateCart();
        }
    }

    public function decrementQuantity($menuId)
    {
        if (isset($this->cart[$menuId]) && $this->cart[$menuId]['quantity'] > 1) {
            $this->cart[$menuId]['quantity']--;
        } else {
            unset($this->cart[$menuId]);
        }
        $this->updateCart();
    }

    private function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $this->taxAmount = $this->subtotal * $this->taxRate;
        $this->totalPrice = $this->subtotal + $this->taxAmount;
    }

    private function updateCart()
    {
        session(['cart' => $this->cart]);
        $this->calculateTotals();
    }

    public function processOrder()
    {
        $this->validate([
            'customerName' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validasi cart masih ada
        if (empty($this->cart)) {
            $this->dispatch('checkout-error', ['message' => 'Keranjang kosong!']);
            return;
        }

        // Validasi menu masih tersedia
        $menuIdsInCart = array_keys($this->cart);
        $existingMenusCount = Menu::whereIn('id', $menuIdsInCart)->count();

        if (count($menuIdsInCart) !== $existingMenusCount) {
            session()->forget('cart');
            $this->dispatch('checkout-error', ['message' => 'Beberapa item sudah tidak tersedia. Silakan pilih ulang.']);
            return redirect()->route('menu.show', ['table' => $this->tableId]);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'table_id' => $this->tableId,
                'customer_name' => $this->customerName ?: 'Pelanggan',
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'tax_rate' => $this->taxRate,
                'total_price' => $this->totalPrice,
                'status' => 'pending',
                'notes' => $this->notes,
                'midtrans_order_id' => 'MW-' . time() . '-' . $this->tableId . '-' . uniqid(),
            ]);

            foreach ($this->cart as $menuId => $item) {
                $order->items()->create([
                    'menu_id' => $menuId,
                    'quantity' => $item['quantity'],
                    'price_at_order' => $item['price'],
                ]);
            }

            DB::commit();
            session()->forget(['cart', 'table_id']);

            // LANGSUNG REDIRECT KE HALAMAN PAYMENT (ganti yang lama)
            return redirect()->route('payment.page', $order->id);

            // Comment/hapus ini:
            // $this->dispatchBrowserEvent('open-midtrans-payment', ['order_id' => $order->id]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            $this->dispatch('checkout-error', ['message' => 'Gagal memproses pesanan']);
        }
    }
}
