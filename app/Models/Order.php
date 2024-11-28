<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public $timestamps = false;

    protected $table = 'order';
    protected $fillable = ['user_id', 'store_id', 'date_time', 'fulfilled'];


public function items()
{
    return $this->hasMany(OrderItem::class, 'order_id', 'id');
}

public function deliveryNotes()
{
    return $this->hasMany(DeliveryNote::class, 'order_id', 'id');
}

public function deliveredItems()
{
    return $this->hasMany(DeliveredItem::class);
}

public function item()
{
    return $this->belongsTo(Item::class);
}


 
}
