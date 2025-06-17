<?php
// Buat komponen baru ini dengan perintah:
// php artisan make:livewire OrderStatusPage

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderStatusPage extends Component
{
    public Order $order;

    public function getListeners()
    {
        // Dengarkan event di channel publik yang spesifik untuk pesanan ini
        return [
            "echo:order-status.{$this->order->id},order.status.updated" => 'refreshOrder',
        ];
    }

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    // Method ini akan dipanggil saat event diterima
    public function refreshOrder()
    {
        // Muat ulang data pesanan dari database
        $this->order->refresh();
    }

    public function render()
    {
        // Kita akan menggunakan view yang sudah ada dari order-finish
        return view('livewire.order-status-page')
               ->layout('layouts.guest');
    }
}
