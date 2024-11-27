<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveredItem extends Model
{
    protected $table = 'delivered_item';
    protected $fillable = ['delivery_note_id', 'item_id', 'quantity'];

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class, 'delivery_note_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

