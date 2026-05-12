<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'titre', 'description', 'objectif', 'montant_collecte',
        'date_debut', 'date_fin', 'active', 'event_id', 'created_by',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'active' => 'boolean',
        'objectif' => 'decimal:2',
        'montant_collecte' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPourcentageAttribute(): float
    {
        if ($this->objectif <= 0) return 0;
        return min(100, round(($this->montant_collecte / $this->objectif) * 100, 1));
    }
}
