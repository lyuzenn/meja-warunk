<?php

namespace App\Providers;
use Livewire\Livewire;
use App\Http\Livewire\Admin\MenuManager;
use App\Http\Livewire\Admin\OrderDashboard;
use App\Http\Livewire\Admin\RatingList;
use App\Http\Livewire\Admin\SalesReport;
use App\Http\Livewire\ShowMenu;
use App\Http\Livewire\RatingForm;
use Illuminate\Support\ServiceProvider;
use App\Http\Livewire\CheckoutPage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // Kode lain mungkin sudah ada di sini...

    // Daftarkan komponen Livewire secara manual
    Livewire::component('admin.menu-manager', MenuManager::class);
    Livewire::component('admin.order-dashboard', OrderDashboard::class);
    Livewire::component('admin.rating-list', RatingList::class);
    Livewire::component('admin.sales-report', SalesReport::class);
    Livewire::component('show-menu', ShowMenu::class);
    Livewire::component('rating-form', RatingForm::class);
    Livewire::component('checkout-page', CheckoutPage::class);
}
}
