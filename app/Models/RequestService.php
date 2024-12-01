<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'serviceprovider_id',
        'date_time',
        'name',
        'price',
        'status',
    ];
}
