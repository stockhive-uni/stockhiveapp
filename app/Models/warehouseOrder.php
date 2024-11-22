<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class warehouseOrder extends Model
{
    //
    protected $table = 'Order';

    public function users() {
        return $this->belongsTo(User::class, 'store_id');
    }

    public function order_items() {
        return $this->hasMany(warehouseOrderedItems::class, 'order_id');
    }
}
