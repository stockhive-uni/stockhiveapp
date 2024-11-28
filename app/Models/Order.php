<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public $timestamps = false;

    protected $table = 'order';

public function items()
{
    return $this->hasMany(OrderItem::class);
}

public function deliveryNotes()
{
    return $this->hasMany(DeliveryNote::class);
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
