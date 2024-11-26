<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deliverynote extends Model
{
    protected $table = 'delivery_note';
    protected $fillable = ['user_id', 'order_id', 'date_time'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function deliveredItems(): HasMany
    {
        return $this->hasMany(DeliveredItem::class, 'delivery_note_id');
    }
}

