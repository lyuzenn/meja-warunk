<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Support\Collection;

class ShowMenu extends Component
{
    public Table $table;
    public $cart = [];

    public $categories;
    public $activeCategory;

    public function mount(Table $table)
    {
        $this->table = $table;
        $this->cart = session('cart', []);

        // Ambil semua kategori yang tersedia dan tetapkan yang pertama sebagai aktif.
        $this->categories = Menu::where('is_available', true)->pluck('category')->unique();
        $this->activeCategory = $this->categories->first();
    }

    public function render()
    {
        // Ambil menu hanya dari kategori yang aktif.
        $menus = Menu::where('is_available', true)
            ->where('category', $this->activeCategory)
            ->get();

        $totalPrice = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $totalItems = collect($this->cart)->sum('quantity');

        return view('livewire.show-menu', [
            'menus' => $menus,
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
        ])->layout('layouts.guest');
    }

    // Fungsi untuk mengubah kategori yang aktif.
    public function setActiveCategory($category)
    {
        $this->activeCategory = $category;
    }

    public function addToCart($menuId)
    {
        $menu = Menu::find($menuId);
        if (!$menu) return;

        // Jika item belum ada di keranjang, tambahkan. Jika sudah, panggil increment.
        if (!isset($this->cart[$menuId])) {
            $this->cart[$menuId] = [
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => 1
            ];
            $this->updateCartAndNotify($menu, "ditambahkan!");
        } else {
            $this->incrementQuantity($menuId);
        }
    }

    // Fungsi untuk menambah jumlah item.
    public function incrementQuantity($menuId)
    {
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
            $this->updateCartAndNotify(Menu::find($menuId));
        }
    }

    // Fungsi untuk mengurangi jumlah item.
    public function decrementQuantity($menuId)
    {
        if (isset($this->cart[$menuId])) {
            if ($this->cart[$menuId]['quantity'] > 1) {
                $this->cart[$menuId]['quantity']--;
            } else {
                unset($this->cart[$menuId]);
            }
            $this->updateCartAndNotify(Menu::find($menuId));
        }
    }

    // Helper untuk memperbarui sesi dan mengirim notifikasi.
    private function updateCartAndNotify(Menu $menu, $message = 'diperbarui!')
    {
        session(['cart' => $this->cart]);
        $this->dispatch('add-to-cart-notification', ['message' => "$menu->name $message"]);
    }

    public function checkout()
    {
        session([
            'cart' => $this->cart,
            'table_id' => $this->table->id
        ]);

        return $this->redirect(route('checkout'), navigate: true);
    }
}
