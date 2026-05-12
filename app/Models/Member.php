<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id', 'nom', 'prenom', 'telephone', 'adresse', 'ville',
        'photo', 'date_adhesion', 'statut', 'role',
    ];

    protected $casts = [
        'date_adhesion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}
