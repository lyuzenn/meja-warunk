<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('customer_name')->nullable()->comment('Opsional, nama pemesan');
            $table->decimal('total_price', 10, 2);
            $table->text('notes')->nullable()->comment('Catatan dari pelanggan');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled', 'paid', 'unpaid'])
                  ->default('unpaid')
                  ->comment('Status pesanan & pembayaran');
            $table->string('midtrans_order_id')->nullable()->index()->comment('ID order dari Midtrans untuk referensi');
            $table->string('snap_token')->nullable()->comment('Token dari Midtrans untuk menampilkan halaman pembayaran');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
