<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        // Pastikan order memiliki item
        $order->load('items.menu');

        $pdf = PDF::loadView('invoice-pdf', compact('order'));
        
        // Nama file: invoice-ORD-123.pdf
        return $pdf->download('invoice-'.$order->midtrans_order_id.'.pdf');
    }
}