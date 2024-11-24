<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order'; 

    protected $fillable = ['user_id', 'store_id', 'date_time'];

    
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
