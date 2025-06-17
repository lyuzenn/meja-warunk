<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Table;
use Livewire\Attributes\Computed;

class ShowMenu extends Component
{
    public Table $table;
    public $cart = [];

    /**
     * Computed property untuk mengambil dan mengelompokkan menu.
     * Hasilnya akan di-cache untuk performa yang lebih baik.
     */
    #[Computed]
    public function menus()
    {
        return Menu::where('is_available', true)
            ->orderBy('category')
            ->get();
    }

    public function mount(Table $table)
    {
        $this->table = $table;
        // Ambil data keranjang dari session saat komponen dimuat.
        $this->cart = session('cart', []);
    }

    public function render()
    {
        return view('livewire.show-menu', [
            'menus' => $this->menus() // Memanggil computed property dan mengirimkannya
        ])->layout('layouts.guest');
    }

    public function addToCart($menuId)
    {
        $menu = Menu::find($menuId);
        if (!$menu) return;

        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
        } else {
            $this->cart[$menuId] = [
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => 1
            ];
        }

        session(['cart' => $this->cart]);

        // Kirim notifikasi ke browser menggunakan dispatch.
        $this->dispatch('add-to-cart-notification', ['message' => "$menu->name ditambahkan ke keranjang!"]);
    }

    /**
     * PERBAIKAN: Menambahkan kembali method checkout yang hilang.
     * Method ini akan menyimpan data ke sesi lalu mengarahkan ke halaman checkout.
     */
    public function checkout()
    {
        session([
            'cart' => $this->cart,
            'table_id' => $this->table->id
        ]);

        return $this->redirect(route('checkout'), navigate: true);
    }
}
