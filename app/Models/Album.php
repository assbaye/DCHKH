<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['nom', 'description', 'cover_image', 'publie', 'created_by'];

    protected $casts = [
        'publie' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(GalleryItem::class);
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
