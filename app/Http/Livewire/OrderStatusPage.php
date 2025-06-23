<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Events\OrderPaid;
use Illuminate\Support\Facades\Log;

class OrderStatusPage extends Component
{
    public Order $order;
    public $showPayButton = false;

    /**
     * PERBAIKAN: Menambahkan titik (.) sebelum nama event
     */
    public function getListeners()
    {
        return [
            "echo:order-status.{$this->order->id},.order.status.updated" => 'refreshOrder',
        ];
    }

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->checkPaymentStatus();
    }

    public function render()
    {
        return view('livewire.order-status-page')
               ->layout('layouts.guest');
    }

    public function checkPaymentStatus()
    {
        // Refresh order dari database
        $this->order = $this->order->fresh();

        // Show pay button jika status belum paid
        $this->showPayButton = in_array($this->order->status, ['pending', 'failed', 'expired', 'cancelled']);

        Log::info('Order status checked:', [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'show_pay_button' => $this->showPayButton
        ]);
    }

    public function refreshOrder()
    {
        $this->order->refresh();
    }

    public function refreshStatus()
    {
        $this->checkPaymentStatus();
        $this->dispatch('status-refreshed', ['status' => $this->order->status]);
    }

    public function goToPayment()
    {
        return redirect()->route('payment.page', $this->order->id);
    }

    public function markAsPaid()
    {
        $this->order->update(['status' => 'paid']);
        broadcast(new OrderPaid($this->order))->toOthers();
        $this->checkPaymentStatus();

        $this->dispatch('payment-completed', ['message' => 'Pesanan berhasil ditandai sebagai dibayar!']);
    }
}
