<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    protected $fillable = [
        'nom', 'categorie', 'quantite', 'quantite_disponible',
        'etat', 'description', 'photo', 'valeur', 'created_by',
    ];

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }

    public function empruntsEnCours()
    {
        return $this->hasMany(Emprunt::class)->whereIn('statut', ['en_cours', 'en_retard']);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
