<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class warehouseOrderedItems extends Model
{
    //
    protected $table = 'order_item';

    public function order_items() {
        return $this->belongsTo('store_item', 'order_id');
    }
}
