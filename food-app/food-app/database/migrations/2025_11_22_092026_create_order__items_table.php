<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_order_items_table.php
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {     
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained(); // Jangan cascade delete, biar histori aman
            $table->integer('quantity'); // Jumlah pesan (misal: 2 porsi)
            $table->decimal('price', 10, 2); // Harga saat dibeli (penting jika harga menu naik di masa depan)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order__items');
    }
};
