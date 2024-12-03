<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class store_item extends Model
{
    //

    public $timestamps = false; //learnt how to disable timestamps which were causing issues when updating records here: https://stackoverflow.com/questions/19937565/disable-laravels-eloquent-timestamps
    protected $table = 'store_item';

    public function store() {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function store_item_storage() {
        return $this->belongsTo(store_item_storage::class, 'id');
    }

}
