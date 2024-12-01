<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryFile extends Model
{
    use HasFactory;
    protected $table = 'story_files';
    protected $fillable = ['serviceprovider_id', 'file', 'ended'];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class, 'serviceprovider_id');
    }

}
