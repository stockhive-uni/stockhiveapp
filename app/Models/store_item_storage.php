<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class store_item_storage extends Model
{
    //
    protected $table = "store_item_storage";

    public function location() {
        return $this->belongsTo(location::class, 'location_id');
    }

    
}
