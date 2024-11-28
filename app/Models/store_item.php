<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class store_item extends Model
{
    //

    protected $table = 'store_item';

    public function store() {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
