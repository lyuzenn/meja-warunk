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
            "echo:admin-dashboard,order.paid" => 'handleNewOrder',
            "echo:admin-dashboard,order.status.updated" => 'refreshOrderList',
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

    /**
     * PERBAIKAN: Method ini sekarang menerima data pesanan ($event)
     * dan mengirimkan perintah notifikasi ke browser.
     */
    public function handleNewOrder($event)
    {
        Log::info('New order event received:', $event);
        $this->loadOrders();

        // Mengirim event ke browser dengan pesan yang dinamis
        $this->dispatch('new-order-notification', [
            'message' => 'Pesanan baru dari Meja ' . ($event['order']['meja']['table_number'] ?? 'N/A')
        ]);
    }

    public function refreshOrderList($event)
    {
        Log::info('Order status update event received:', $event);
        $this->loadOrders();
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return;
        }

        try {
            $order->status = $status;
            $order->save();
            broadcast(new OrderStatusUpdated($order))->toOthers();
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
