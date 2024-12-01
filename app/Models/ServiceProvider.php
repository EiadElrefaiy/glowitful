<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    protected $table = 'service_providers';
    protected $fillable = ['user_id', 'location_lat', 'location_long', 'address', 'image', 'review', 'type'];

    // Define relationships if needed

    public function storyFiles()
    {
        return $this->hasMany(StoryFile::class, 'serviceprovider_id');
    }

    public function Services()
    {
        return $this->hasMany(Service::class, 'serviceprovider_id');
    }
}
