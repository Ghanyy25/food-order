<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Kita izinkan semua field diisi (kecuali id & timestamps) agar coding lebih cepat
    protected $guarded = ['id'];

    // Order punya banyak item
    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
