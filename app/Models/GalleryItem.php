<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'titre', 'description', 'type', 'fichier',
        'url_video', 'album', 'event_id', 'publie', 'created_by',
    ];

    protected $casts = [
        'publie' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublie($query)
    {
        return $query->where('publie', true);
    }
}
