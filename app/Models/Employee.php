<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    //when making database calls this is the table it will access when models are referenced
    protected $table = 'users';

    public function store() {
        return $this->belongsTo(Store::class, 'store_id'); //foreign key 'id' in the store table
    }
}
