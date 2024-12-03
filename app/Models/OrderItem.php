<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsTo(Item::class, 'item_id');
    }
    
    public function deliveredItems()
    {
        return $this->hasMany(DeliveredItem::class, 'order_item_id', 'id');
    }
}
