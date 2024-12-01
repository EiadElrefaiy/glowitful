<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'serviceprovider_id',
        'user_id',
        'total',
        'type',
        'status',
        'date_time',
        'duration_hours',
        'duration_minutes'
    ];

    public function orderServices()
    {
        return $this->hasMany(OrderServices::class, 'order_id');
    }

}
