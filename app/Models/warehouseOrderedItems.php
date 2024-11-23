<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class warehouseOrderedItems extends Model
{
    //
    protected $table = 'order_item';

    public function items() {
        $this->hasMany(Item::class, 'item_id');
    }

    public function order() {
        $this->belongsTo(warehouseOrder::class, 'order_id');
    }
}
