<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverDelivery extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'over_deliveries';

    public $incrementing = false;
    protected $primaryKey = 'delivery_note_id';
    protected $fillable = ['delivery_note_id', 'item_id', 'store_id', 'returned', 'quantity', 'date_time'];

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
