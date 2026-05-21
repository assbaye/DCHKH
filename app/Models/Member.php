<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id', 'nom', 'prenom', 'telephone', 'adresse', 'ville',
        'photo', 'date_adhesion', 'statut', 'role', 'matricule',
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

    public static function genererMatricule(): string
    {
        $dernier = self::whereNotNull('matricule')
            ->orderByRaw("CAST(SUBSTRING(matricule, 7) AS UNSIGNED) DESC")
            ->value('matricule');

        $numero = $dernier ? (int) substr($dernier, 6) + 1 : 1;

        return 'DCHKH-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
