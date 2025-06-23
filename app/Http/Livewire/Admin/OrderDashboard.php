<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderDashboard extends Component
{
    public $orders;

    public function getListeners()
    {
        return [
            "echo:admin-dashboard,.order.paid" => 'handleNewOrder',
            "echo:admin-dashboard,.order.status.updated" => 'refreshOrderList',
        ];
    }

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Order::with('table', 'items.menu')
            ->whereDate('created_at', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->get();
    }

    public function handleNewOrder($event)
    {
        // PERBAIKAN: Menambahkan logging detail untuk debugging
        Log::info('[LIVEWIRE DEBUG] Menerima event "order.paid". Payload mentah:', $event);

        $this->loadOrders();

        // Mengambil nomor meja dari data event dengan aman
        $tableNumber = data_get($event, 'order.table.table_number', 'N/A');

        Log::info('[LIVEWIRE DEBUG] Nomor Meja yang diekstrak: ' . $tableNumber);

        $this->dispatch('new-order-notification', [
            'message' => 'Pesanan baru dari Meja ' . $tableNumber
        ]);
    }

    public function refreshOrderList($event)
    {
        $orderId = data_get($event, 'order.id');
        if ($this->orders->contains('id', $orderId)) {
            Log::info('Order status update event received BY LIVEWIRE:', $event);
            $this->loadOrders();
        }
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if (!$order) return;

        try {
            $order->status = $status;
            $order->save();
            broadcast(new OrderStatusUpdated($order->load('table')))->toOthers();
            $this->loadOrders();
        } catch (Throwable $e) {
            Log::error('Failed to update or broadcast order status: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.order-dashboard')
            ->layout('layouts.app');
    }
}
