<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $fillable = [
        'titre', 'date_reunion', 'heure_debut', 'heure_fin',
        'lieu', 'ordre_du_jour', 'compte_rendu', 'statut', 'created_by',
    ];

    protected $casts = [
        'date_reunion' => 'date',
    ];

    public function membres()
    {
        return $this->belongsToMany(Member::class, 'reunion_membres');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
