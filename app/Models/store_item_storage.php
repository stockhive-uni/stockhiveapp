<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class store_item_storage extends Model
{
    //
    public $timestamps = false;
    protected $table = "store_item_storage";

    protected $fillable = [
        'store_item_id',
        'quantity',
        'location_id',
    ];
    
    public function location() {
        return $this->belongsTo(location::class, 'location_id');
    }

    public function store_item() {
        return $this->belongsTo(store_item::class, 'store_item_id');
    }
}
