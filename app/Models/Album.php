<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Album extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'name','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')->useDisk('public');
    }
    
}
