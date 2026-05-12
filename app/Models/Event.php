<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'titre', 'description', 'categorie', 'date_event',
        'heure_event', 'lieu', 'image', 'publie', 'created_by',
    ];

    protected $casts = [
        'date_event' => 'date',
        'publie' => 'boolean',
    ];

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function galerie()
    {
        return $this->hasMany(GalleryItem::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_event', '>=', now())->where('publie', true);
    }

    public function scopePublie($query)
    {
        return $query->where('publie', true);
    }
}
