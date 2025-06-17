<?php

namespace App\Events;

use App\Models\Order; // <-- Jangan lupa import model Order Anda
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaid implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Properti publik ini akan otomatis dikirim sebagai data event.
     * @var Order
     */
    public $order;

    /**
     * Membuat instance event baru.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Mengembalikan data yang ingin Anda siarkan.
     * Sangat direkomendasikan untuk mengontrol data yang dikirim,
     * agar tidak membocorkan data sensitif.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        // Muat relasi 'items' jika belum dimuat
        $this->order->load('items.menu');

        return [
            'id' => $this->order->id,
            'pemesan' => $this->order->pemesan, // Ganti dengan nama kolom Anda
            'meja' => $this->order->meja,       // Ganti dengan nama kolom Anda
            'total' => $this->order->total,
            'status' => $this->order->status,
            'created_at_human' => $this->order->created_at->diffForHumans(), // Contoh: '5 seconds ago'
            'items' => $this->order->items->map(function ($item) {
                return [
                    'menu_name' => $item->menu->nama, // Asumsi ada relasi menu->nama
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
        ];
    }

    /**
     * Mendefinisikan nama alias untuk event.
     * Di JavaScript, Anda akan mendengarkan 'order.paid' bukan 'App\Events\OrderPaid'.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'order.paid';
    }

    /**
     * Mendapatkan channel (saluran) tempat event akan disiarkan.
     * Channel 'dashboard' ini bersifat publik, semua yang mendengarkan akan menerima event.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
         return new Channel('admin-dashboard');
    }
}
