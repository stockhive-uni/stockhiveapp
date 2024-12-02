<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model
{
    protected $table = 'order_item';
    protected $fillable = ['order_id', 'item_id', 'ordered', 'price'];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function deliveredItems()
    {
        return $this->hasMany(DeliveredItem::class, 'item_id', 'item_id');
    }
}

