<?php


namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReport extends Component
{
    public $period = 'last_7_days';

    // Properti untuk summary cards dan tabel
    public $totalRevenue = 0;
    public $totalOrders = 0;
    public $totalItemsSold = 0;
    public $averageOrderValue = 0;
    public $topSellingMenus = [];

    // Properti untuk data grafik, akan diisi saat render
    public $salesDataForChart = [];

    public function mount()
    {
        // Panggil sekali saat komponen dimuat pertama kali
        $this->generateReport();
    }

    // Hook ini akan terpanggil setiap kali dropdown $period berubah
    public function updatedPeriod()
    {
        $this->generateReport();
        // Kirim event untuk update chart di frontend
        $this->dispatch('updateChart', data: $this->salesDataForChart);
    }

    // Method utama untuk menghitung semua statistik dan data
    public function generateReport()
    {
        list($startDate, $endDate) = $this->calculateDateRange();

        $ordersQuery = Order::whereIn('status', ['paid', 'completed', 'processing'])
                            ->whereBetween('created_at', [$startDate, $endDate]);

        // Hitung data untuk Summary Cards
        $this->totalRevenue = (clone $ordersQuery)->sum('total_price');
        $this->totalOrders = (clone $ordersQuery)->count();
        $this->averageOrderValue = ($this->totalOrders > 0) ? $this->totalRevenue / $this->totalOrders : 0;

        $orderIds = (clone $ordersQuery)->pluck('id');
        $this->totalItemsSold = OrderItem::whereIn('order_id', $orderIds)->sum('quantity');

        // Hitung data untuk tabel Menu Terlaris
        $this->topSellingMenus = OrderItem::with('menu')
            ->whereIn('order_id', $orderIds)
            ->select('menu_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(price_at_order * quantity) as total_revenue'))
            ->groupBy('menu_id')->orderBy('total_quantity', 'desc')->limit(10)->get();

        // Siapkan data untuk dikirim ke Chart.js
        $salesByDay = Order::whereIn('status', ['paid', 'completed', 'processing'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')->orderBy('date', 'asc')->get()->pluck('total', 'date');

        $data = ['labels' => [], 'data' => []];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->toDateString();
            $data['labels'][] = $currentDate->format('d M');
            $data['data'][] = $salesByDay[$dateString] ?? 0;
            $currentDate->addDay();
        }
        $this->salesDataForChart = $data;
    }

    // Helper untuk menghitung rentang tanggal
    private function calculateDateRange(): array
    {
        return match ($this->period) {
            'today' => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
            'last_30_days' => [Carbon::now()->subDays(29)->startOfDay(), Carbon::now()->endOfDay()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            default => [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()],
        };
    }

    public function render()
    {
        return view('livewire.admin.sales-report')
            ->layout('layouts.app');
    }
}
