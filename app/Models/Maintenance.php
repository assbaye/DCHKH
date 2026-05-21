<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'materiel_id', 'titre', 'description',
        'cout', 'date_maintenance', 'statut', 'enregistre_par',
    ];

    protected $casts = [
        'date_maintenance' => 'date',
    ];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }
}
