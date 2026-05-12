<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khassaide extends Model
{
    protected $fillable = [
        'titre', 'auteur', 'interprete', 'description', 'paroles',
        'fichier_audio', 'langue', 'telechargeable', 'publie', 'created_by',
    ];

    protected $casts = [
        'telechargeable' => 'boolean',
        'publie' => 'boolean',
    ];

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublie($query)
    {
        return $query->where('publie', true);
    }
}
