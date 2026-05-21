<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'type', 'message', 'nb_destinataires',
        'nb_succes', 'nb_echecs', 'envoye_par', 'reunion_id',
    ];

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'envoye_par');
    }

    public function reunion()
    {
        return $this->belongsTo(Reunion::class);
    }
}
