<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_orders_table.php
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name'); // Nama Pembeli
            $table->string('table_number')->nullable(); // Kalau dine-in
            $table->decimal('total_price', 10, 2);
            // Status pesanan (pending -> dikerjakan -> selesai)
            $table->enum('status', ['pending', 'cooking', 'completed', 'cancel'])->default('pending');
            $table->timestamps();
        });
    }
        /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
