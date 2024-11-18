<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Item extends Model
{
    use HasFactory, Notifiable;

    //when making database calls this is the table it will access when models are referenced
    protected $table = 'item';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'department_id'
    ];

    public function department() {
        return $this->belongsTo(department::class, 'department_id'); //foreign key 'id' in the department table
    }
}
