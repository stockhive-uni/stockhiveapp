<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    //
    protected $table = 'department';

    public function item() {
        return $this->hasMany(Item::class, 'id'); //foreign key 'id' in the item table
    }
}
