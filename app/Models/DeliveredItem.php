<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveredItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'delivered_item';
    protected $fillable = ['delivery_note_id', 'item_id', 'quantity'];


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class, 'delivery_note_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');  
    }

}
