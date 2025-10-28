<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fahrzeuge extends Model
{
    protected $table = 'fahrzeuge';
    protected $primaryKey = 'fahrzeug_id';

    /** kein Auto-Increment, weil HS01, GH02 … */
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'fahrzeug_id', 'transportauftrag_id', 'type', 'status', 'zeitstempel','ladestand','akkuzustand','akt_ta','meldung', 'x', 'y'
    ];
    // In app/Models/Fahrzeuge.php
    public function route()
    {
        return $this->hasOne(Routen::class, 'fahrzeug_id');
    }
    
    public function transportauftrag_id()
    {
        return $this->belongsTo(Auftraege::class, 'transportauftrag_id');
    }

    // Gibt eine String-Meldung zurück (für die Tables)
    public function getMeldung(): string
    {

    if ($this->ladestand < 20) {
        return "Batterie Niedrig";
    }

    if (mb_strtolower($this->status) === 'außer betrieb') {
        return "Technische Wartung";
    }

    if (mb_strtolower($this->status) === 'störung') {
        return "Route Blockiert";
    }

    if (mb_strtolower($this->status) === 'nicht erreichbar') {
        return "Nicht Erreichbar";
    }

    return 'Keine';
    }

    
// Überschreibt die meldung-Spalte mit dem berechneten Wert aus getMeldung()
public function getMeldungAttribute()
{
    return $this->getMeldung();
}



//Gibt eine HTML-Benachrichtigung zurück (für die Notfiikationen)
public function getNotification(): ?string
{

    if ($this->ladestand < 20) {
        return <<<HTML
<span class="d-flex align-items-center gap-3">
    <i class="bi bi-battery text-danger fs-5"></i>
    <span>Fahrzeug <strong>{$this->fahrzeug_id}</strong> hat nur noch <span class="text-danger fw-bold">{$this->ladestand}</span> Batterie.</span>
</span>
HTML;
    }

    if (mb_strtolower($this->status) === 'außer betrieb') {
        return <<<HTML
<span class="d-flex align-items-center gap-3">
    <i class="bi bi-tools text-danger fs-5"></i>
    <span>Fahrzeug <strong>{$this->fahrzeug_id}</strong> ist <span class="text-danger fw-bold">außer Betrieb</span>.</span>
</span>
HTML;
    }

    if (mb_strtolower($this->status) === 'störung') {
        return <<<HTML
<span class="d-flex align-items-center gap-3">
    <i class="bi bi-dash-square text-danger fs-5"></i>
    <span> Route für Fahrzeug <strong>{$this->fahrzeug_id}</strong> <span class="text-danger fw-bold">blockiert</span>.</span>
</span>
HTML;
    }

    if (mb_strtolower($this->status) === 'nicht erreichbar') {
        return <<<HTML
<span class="d-flex align-items-center gap-3">
    <i class="bi bi-wifi-off text-danger fs-5"></i>
    <span> Fahrzeug <strong>{$this->fahrzeug_id}</strong> ist <span class="text-danger fw-bold"> nicht erreichbar</span>.</span>
</span>
HTML;
    }

    return null;
}

//public function aktiveMeldungen()
//{
  //  return $this->meldungen()->where('status', 'offen');
//}

// Beziehung: Ein Fahrzeug kann mehrere Meldungen haben
public function meldungen()
{
    return $this->hasMany(Meldung::class, 'fahrzeug_id', 'fahrzeug_id');
}

}

