<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deliverynote extends Model
{
    public $timestamps = false;

    protected $table = 'delivery_note';
    protected $fillable = ['id', 'ordered_quantity', 'user_id', 'order_id', 'date_time'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    
    public function deliveredItems()
    {
        return $this->hasMany(DeliveredItem::class, 'delivery_note_id');
    }
    

    public function item()
    {
        return $this->belongsTo(Item::class);
    }



    public function overDeliveries()
    {
        return $this->hasMany(OverDelivery::class);
    }
}

