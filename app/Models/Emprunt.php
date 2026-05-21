<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    protected $fillable = [
        'materiel_id', 'member_id', 'quantite',
        'date_emprunt', 'date_retour_prevue', 'date_retour_effective',
        'statut', 'notes', 'enregistre_par',
    ];

    protected $casts = [
        'date_emprunt'           => 'date',
        'date_retour_prevue'     => 'date',
        'date_retour_effective'  => 'date',
    ];

    protected static function booted(): void
    {
        // Met à jour le statut en_retard automatiquement
        static::retrieved(function (Emprunt $emprunt) {
            if ($emprunt->statut === 'en_cours' && $emprunt->date_retour_prevue < now()->startOfDay()) {
                $emprunt->updateQuietly(['statut' => 'en_retard']);
            }
        });
    }

    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }

    public function membre()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }
}
