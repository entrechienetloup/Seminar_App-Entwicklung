<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meldung extends Model
{

    protected $table = 'meldungen';

    protected $fillable = [
        'fahrzeug_id',
        'typ',
        'beschreibung',
        'gemeldet_am',
        //'status',
    ];
    
    // Konvertiert 'gemeldet_am' in ein Carbon-Datum
    protected $casts = [
        'gemeldet_am' => 'datetime',
    ];

    //Beziehung: Meldung gehört zu einem Fahrzeug 
    public function fahrzeug()
    {
        return $this->belongsTo(Fahrzeug::class, 'fahrzeug_id', 'fahrzeug_id');
    }
}