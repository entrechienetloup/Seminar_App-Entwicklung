<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lagerplatz;

class Routen extends Model
{
    protected $fillable = ['fahrzeug_id','zielort_id', 'startort_id'];
    protected $table = 'routen';
    
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

   public function routenpunkte()
{
    return $this->belongsToMany(Routenpunkte::class, 'route_routenpunkt', 'routen_id', 'routenpunkte_id')
                ->withPivot('reihenfolge')
                ->orderBy('pivot_reihenfolge');
}

}
