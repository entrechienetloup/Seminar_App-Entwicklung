<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routenpunkte extends Model
{
    protected $table = 'routenpunkte';
    protected $fillable = ['routenpunkte_id', 'x', 'y'];
    protected $primaryKey = 'routenpunkte_id';
    public $incrementing = false;
    protected $keyType = 'string';

}

