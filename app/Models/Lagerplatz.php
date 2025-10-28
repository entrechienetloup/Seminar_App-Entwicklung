<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lagerplatz extends Model //Lagerplätze Datenbank
{
    protected $fillable = ['lagerplatz_id','x', 'y', 'hoehe', 'breite', 'type', 'anzahl', 'belegt'];
    protected $table = 'lagerplaetze';    protected $primaryKey = 'lagerplatz_id';
     public    $incrementing = false;        
    protected $keyType      = 'string';

    public function Start()
{
    return $this->hasMany(Routen::class, 'startort_id', 'lagerplatz_id');
}

public function Ziel()
{
    return $this->hasMany(Routen::class, 'zielort_id', 'lagerplatz_id');
}
}

