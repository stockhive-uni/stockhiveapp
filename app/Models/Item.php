<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'item';
    protected $fillable = ['name', 'price', 'quantity'];



    public function deliveredItems()
    {
        return $this->hasMany(DeliveredItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}



