<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->order->id,
            'status' => $this->order->status,
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.status.updated';
    }

    /**
     * PERBAIKAN UTAMA:
     * Mengirim event ke DUA channel:
     * 1. Channel publik spesifik untuk pelanggan.
     * 2. Channel publik umum untuk semua admin di dashboard.
     */
    public function broadcastOn()
    {
        return [
            new Channel("order-status.{$this->order->id}"),
            new Channel("admin-dashboard"),
        ];
    }
}
