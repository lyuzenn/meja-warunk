<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan Anda mengimpor fasad PDF

class InvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function download(Order $order)
    {
        // Memuat view 'invoice-pdf' dengan data pesanan ($order)
        $pdf = Pdf::loadView('invoice-pdf', compact('order'));

        // Mengatur nama file yang akan diunduh
        $fileName = 'nota-' . $order->id . '-' . now()->format('Y-m-d') . '.pdf';

        // Mengirimkan PDF ke browser untuk diunduh
        return $pdf->download($fileName);
    }
}
