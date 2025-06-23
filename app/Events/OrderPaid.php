<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaid implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        // PERBAIKAN: Memuat relasi 'table' saat event dibuat
        $this->order = $order->load('table');
    }

    /**
     * Mengembalikan data yang ingin disiarkan.
     */
    public function broadcastWith(): array
    {
        return [
            // Kita hanya mengirim data yang dibutuhkan, bukan seluruh objek.
            'order' => [
                'id' => $this->order->id,
                'table' => [
                    'table_number' => $this->order->table->table_number,
                ]
            ]
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.paid';
    }

    public function broadcastOn()
    {
        return new Channel('admin-dashboard');
    }
}
