<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    public function transaction_item() {
        return $this->hasMany(transaction_item::class, 'item_id');
    } 
}
