<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderServices extends Model
{
    use HasFactory;
    protected $table = 'order_services';
    protected $fillable = [
        'order_id',
        'name',
        'price',
        'type',
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
