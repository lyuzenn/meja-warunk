<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\MenuManager;
use App\Http\Livewire\Admin\OrderDashboard;
use App\Http\Livewire\Admin\RatingList;
use App\Http\Livewire\Admin\SalesReport;
use App\Http\Livewire\ShowMenu;
use App\Http\Livewire\RatingForm;
use App\Http\Livewire\OrderStatusPage;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MidtransController;



//======================================================================
// RUTE PUBLIK (Untuk Pelanggan)
//======================================================================

// Arahkan halaman utama ke meja default (meja 1)
Route::get('/', function() {
    return redirect()->route('menu.show', ['table' => '1']);
});

// Halaman utama untuk pelanggan melihat menu berdasarkan nomor meja
Route::get('/meja/{table:table_number}', ShowMenu::class)->name('menu.show');

// Halaman untuk proses checkout
Route::get('/checkout', \App\Http\Livewire\CheckoutPage::class)->name('checkout');


// Halaman untuk pelanggan melihat status pesanan mereka secara real-time
Route::get('/order/{order}/status', OrderStatusPage::class)->name('order.finish');

// Halaman untuk pelanggan memberikan rating setelah pesanan selesai
Route::get('/order/{order}/rate', RatingForm::class)->name('order.rate');

// Endpoint untuk mengunduh invoice/struk pesanan
Route::get('/order/{order}/invoice', [InvoiceController::class, 'download'])->name('order.invoice');


//======================================================================
// RUTE ADMIN (Memerlukan Login)
//======================================================================

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard utama admin (pesanan masuk)
        Route::get('/dashboard', OrderDashboard::class)->name('dashboard');

        // Halaman manajemen menu (tambah, edit, hapus menu)
        Route::get('/menus', MenuManager::class)->name('menus');

        // Halaman laporan penjualan
        Route::get('/reports', SalesReport::class)->name('reports');

        // Halaman untuk melihat rating dan ulasan dari pelanggan
        Route::get('/ratings', RatingList::class)->name('ratings');
});


//======================================================================
// PENGALIHAN (REDIRECT) & WEBHOOK
//======================================================================

// Arahkan route default /dashboard Laravel ke /admin/dashboard
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function(){
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// Rute Webhook (Endpoint untuk menerima notifikasi dari Midtrans)
Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler'])->name('midtrans.notification');
