<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auftraege extends Model
{
    protected $table      = 'auftraege';
    protected $primaryKey = 'transportauftrag_id';  //  ←  PK festlegen
    public    $incrementing = false;                //  keine Auto-Increment-Zahl
    protected $keyType      = 'string';
    protected $fillable = [
        'transportauftrag_id','status','prioritaet','startort_id','zielort_id','fahrzeug_id'
    ];
       public function fahrzeug()
    {
        return $this->belongsTo(Fahrzeuge::class, 'fahrzeug_id');
    }
    public function startort()
    {
        return $this->belongsTo(Lagerplatz::class, 'startort_id','lagerplatz_id');
    }

    public function zielort()
    {
        return $this->belongsTo(Lagerplatz::class, 'zielort_id','lagerplatz_id');
    }
}

