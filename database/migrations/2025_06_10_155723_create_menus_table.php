<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->comment('Harga menu');
            $table->string('image_url')->nullable()->comment('Link ke foto menu');
            $table->string('category')->default('Uncategorized')->comment('Kategori: Makanan, Minuman, Snack');
            $table->boolean('is_available')->default(true)->comment('Status ketersediaan: true=Tersedia, false=Habis');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
