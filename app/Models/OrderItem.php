<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_item'; 

    protected $fillable = ['order_id', 'item_id', 'ordered', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
