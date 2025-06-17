<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Menu;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class MenuManager extends Component
{
    // Menggunakan trait dari Livewire untuk menangani upload file.
    use WithFileUploads;

    // Properti untuk menampung semua menu yang akan ditampilkan.
    public $menus;

    // Properti untuk form (baik untuk membuat baru maupun mengedit).
    public $menuId;
    public $name;
    public $description;
    public $price;
    public $category;
    public $is_available = true; // Defaultnya, menu tersedia.

    // Properti khusus untuk menangani gambar.
    public $newImage;        // Untuk menampung file baru yang di-upload.
    public $existingImage;   // Untuk menyimpan path gambar yang sudah ada saat mengedit.

    // Properti untuk mengontrol state dari modal/dialog.
    public $isModalOpen = false;

    /**
     * Method yang dipanggil saat komponen pertama kali dimuat.
     */
    public function mount()
    {
        $this->loadMenus();
    }

    /**
     * Method untuk mengambil data menu dari database.
     */
    public function loadMenus()
    {
        $this->menus = Menu::latest()->get();
    }

    /**
     * Method utama untuk merender tampilan komponen.
     */
    public function render()
    {
        return view('livewire.admin.menu-manager')
               ->layout('layouts.app'); // Menggunakan layout utama aplikasi.
    }

    /**
     * Membuka modal dalam mode "buat baru".
     */
    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    /**
     * Membuka modal dalam mode "edit" dan mengisi data yang ada.
     * @param int $id
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->menuId = $id;
        $this->name = $menu->name;
        $this->description = $menu->description;
        $this->price = $menu->price;
        $this->category = $menu->category;
        $this->is_available = $menu->is_available;
        $this->existingImage = $menu->image; // Simpan path gambar yang sudah ada.
        $this->newImage = null; // Reset input file.

        $this->isModalOpen = true;
    }

    /**
     * Menutup modal dan mereset semua field input.
     */
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    /**
     * Method privat untuk membersihkan semua field form.
     */
    private function resetInputFields()
    {
        $this->menuId = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->category = '';
        $this->is_available = true;
        $this->newImage = null;
        $this->existingImage = null;
    }

    /**
     * Menyimpan data menu (baik baru maupun editan) ke database.
     */
    public function saveMenu()
    {
        // Validasi input.
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048', // Maksimal 2MB.
        ]);

        $imagePath = $this->existingImage;

        // Jika ada gambar baru yang di-upload...
        if ($this->newImage) {
            // Hapus gambar lama jika ada.
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            // Simpan gambar baru dan dapatkan path-nya.
            $imagePath = $this->newImage->store('menu-images', 'public');
        }

        // Gunakan updateOrCreate untuk membuat atau mengupdate record.
        Menu::updateOrCreate(['id' => $this->menuId], [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'is_available' => $this->is_available,
            'image' => $imagePath,
        ]);

        // Kirim notifikasi flash ke session.
        session()->flash('message',
            $this->menuId ? 'Menu berhasil diperbarui.' : 'Menu berhasil ditambahkan.');

        $this->closeModal();
        $this->loadMenus(); // Muat ulang daftar menu.
    }

    /**
     * Menghapus menu dari database.
     * @param int $id
     */
    public function delete($id)
    {
        $menu = Menu::find($id);

        if ($menu) {
            // Hapus file gambar dari storage jika ada.
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            // Hapus record dari database.
            $menu->delete();
            session()->flash('message', 'Menu berhasil dihapus.');
        }

        $this->loadMenus(); // Muat ulang daftar menu.
    }
}
