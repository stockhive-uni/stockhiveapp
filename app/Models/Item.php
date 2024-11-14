<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //when making database calls this is the table it will access when models are referenced
    protected $table = 'item';

    public function department() {
        return $this->belongsTo(department::class, 'department_id'); //foreign key 'id' in the department table
    }
}
