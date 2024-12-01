<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $fillable = ['serviceprovider_id', 'name', 'image' , 'price', 'type' ,'duration_hours', 'duration_minutes'];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class, 'serviceprovider_id');
    }
}
