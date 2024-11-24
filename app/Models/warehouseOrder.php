<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class warehouseOrder extends Model
{
    //
    protected $table = 'Order';

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order_item() {
        return $this->hasMany(warehouseOrderedItems::class, 'order_id');
    }

    public function store() {
        $this->belongsTo(Store::class, 'store_id');
    }

}
