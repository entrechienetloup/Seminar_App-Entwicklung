<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route_Routenpunkte extends Model
{
    public function routenpunkte_id()
    {
        return $this->belongsTo(Routenpunkte::class, 'routenpunkte_id');
    }
}
