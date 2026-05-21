<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable = [
        'titre', 'categorie', 'montant',
        'date_depense', 'description', 'justificatif', 'enregistre_par',
    ];

    protected $casts = [
        'date_depense' => 'date',
    ];

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }
}
