<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderStatusPage extends Component
{
    public Order $order;

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
    }

    public function refreshOrder()
    {
        $this->order->refresh();
    }

    public function render()
    {
        return view('livewire.order-status-page')
               ->layout('layouts.guest');
    }
}
