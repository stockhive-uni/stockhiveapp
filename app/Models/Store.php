<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $table = 'store';

    public function employee() {
        return $this->hasMany(Employee::class, 'id'); //foreign key 'id' in the employee table
    }
}
