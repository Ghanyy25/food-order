<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    // Item milik satu order
    public function order() {
        return $this->belongsTo(Order::class);
    }

    // Item terhubung ke produk (untuk ambil nama/gambar)
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
