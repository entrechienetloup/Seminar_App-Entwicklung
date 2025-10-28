<?php
namespace App\Http\Controllers;

use App\Models\Routenpunkte;
use Illuminate\Http\Request;

class RoutePunktController extends Controller
{
public function store(Request $request) //Für BearbeitenForm Routenpunkte speichern
{
    $request->validate([
        'x' => 'required|integer',
        'y' => 'required|integer',
    ]);

    Routenpunkte::firstOrCreate([
        'x' => $request->x,
        'y' => $request->y,
    ]);

    return back()->with('success', 'Routenpunkt gespeichert.');
}

}