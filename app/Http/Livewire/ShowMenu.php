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

    public Collection $categories;
    public $activeCategory;

    public function mount(Table $table)
    {
        $this->table = $table;
        $this->cart = session('cart', []);

        // Ambil semua kategori unik yang tersedia dari database.
        $this->categories = Menu::where('is_available', true)->pluck('category')->unique();
        // Tetapkan 'Semua' sebagai kategori aktif secara default.
        $this->activeCategory = 'Semua';
    }

    public function render()
    {
        // Mulai query untuk mengambil menu yang tersedia.
        $query = Menu::where('is_available', true);

        // Jika kategori yang aktif BUKAN 'Semua', tambahkan filter where.
        if ($this->activeCategory !== 'Semua') {
            $query->where('category', $this->activeCategory);
        }

        // Ambil data menu dan kirim ke view.
        $menus = $query->orderBy('category')->get();

        $totalPrice = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $totalItems = collect($this->cart)->sum('quantity');

        return view('livewire.show-menu', [
            'menus' => $menus,
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
        ])->layout('layouts.guest');
    }

    // Fungsi untuk mengubah kategori aktif yang dipilih pengguna.
    public function setActiveCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function addToCart(int $menuId): void
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

    public function incrementQuantity(int $menuId): void
    {
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
            $this->updateCartAndNotify(Menu::find($menuId));
        }
    }

    public function decrementQuantity(int $menuId): void
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

    private function updateCartAndNotify(Menu $menu, string $message = 'diperbarui!'): void
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
