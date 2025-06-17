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

    /**
     * PERBAIKAN:
     * Listener untuk update status sekarang mendengarkan di channel 'admin-dashboard'.
     */
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

    public function handleNewOrder($event)
    {
        Log::info('New order event received via Echo:', $event);
        $this->loadOrders();
    }

    public function refreshOrderList($event)
    {
        // Hanya refresh jika status order yang diupdate ada di daftar kita
        if ($this->orders->contains('id', $event['id'])) {
            Log::info('Order status update event received via Echo:', $event);
            $this->loadOrders();
        }
    }

    public function updateOrderStatus($orderId, $status)
    {
        $orderToUpdate = Order::find($orderId);
        if (!$orderToUpdate) {
            Log::error("Attempted to update a non-existent order with ID: {$orderId}");
            return;
        }

        try {
            $orderToUpdate->status = $status;
            $orderToUpdate->save();

            // Mengirim event yang sekarang akan ke 2 channel
            broadcast(new OrderStatusUpdated($orderToUpdate))->toOthers();

            // Langsung update UI di dashboard admin
            $this->loadOrders();

        } catch (Throwable $e) {
            Log::error('EXCEPTION during updateOrderStatus: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.order-dashboard')
            ->layout('layouts.app');
    }
}
