<?php
namespace App\Http\Controllers;

use App\Models\Fahrzeuge;
use App\Models\Auftraege;
use App\Models\Lagerplatz;
use App\Models\Routen;
use App\Models\Routenpunkte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RouteController extends Controller
{
public function zeigeRoute($fahrzeugId) //Funktion zum Anzeigen der Rotue onclick
    {
        try {
            // Laden der Route mit Routenpunkte Startort Zielort
            $route = Routen::with(['routenpunkte', 'startort', 'zielort'])
                ->where('fahrzeug_id', $fahrzeugId)
                ->first();
            $routenpunkte = $route->routenpunkte
                ->sortBy(function ($punkt) {
                    return $punkt->pivot->reihenfolge;
                })
                ->values()
                ->map(function ($punkt) {
                    return [
                        'x' => $punkt->x,
                        'y' => $punkt->y,
                    ];
                });

        $startort_idCoords = [ //Startort festlegen mit offset, damit im Lagerplatz mittig
            'x' => ($route->startort->x ?? 0) + (($route->startort->breite ?? 0) / 2),
            'y' => ($route->startort->y ?? 0) + (($route->startort->hoehe ?? 0) / 2),
        ];

        $zielort_idCoords = [//Zielort festlegen mit offset, damit im Lagerplatz mittig
            'x' => ($route->zielort->x ?? 0) + (($route->zielort->breite ?? 0) / 2),
            'y' => ($route->zielort->y ?? 0) + (($route->zielort->hoehe ?? 0) / 2),
        ];

        // Route wird erstellt
        $kompletteRoute = collect();
        $kompletteRoute->push($startort_idCoords); // Startort
        $kompletteRoute = $kompletteRoute->merge($routenpunkte); // Routenpunkte 
        $kompletteRoute->push($zielort_idCoords); // Zielort

        return response()->json([
            'route' => $kompletteRoute, // Das Haupt-Array für die Karte
            'startort_id' => $startort_idCoords, // optional
            'zielort_id' => $zielort_idCoords,   // optional
        ]);

        } catch (\Throwable $e) { //Fehlersuche
            Log::error("Unerwarteter Fehler in zeigeRoute für Fahrzeug-ID {$fahrzeugId}: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['fehler' => 'Ein unerwarteter Serverfehler ist aufgetreten.'], 500);
        }
    }
public function store(Request $request) //Zum Selbererstellen der Route über /bearbeiten nicht über Route anpassen Funktion
{
    $request->validate([
        'fahrzeug_id' => 'required|string',
        'routenpunkte' => 'required|string',
    ]);

    $fahrzeugId = $request->fahrzeug_id;
    $punktIds = explode(',', $request->routenpunkte);

    // Aktuelle Route des Fahrzeugs holen
    $aktuelleRoute = Routen::where('fahrzeug_id', $fahrzeugId)->latest()->first();

    if (!$aktuelleRoute) {
        return response()->json(['message' => 'Keine bestehende Route für dieses Fahrzeug gefunden.'], 404);
    }

    // Alte Routenpunkte löschen (optional, falls du überschreiben willst)
    $aktuelleRoute->routenpunkte()->detach();

    // Neue Punkte anhängen
    foreach ($punktIds as $index => $punktId) {
        $punkt = Routenpunkte::where('routenpunkte_id', $punktId)->first();
        if ($punkt) {
            $aktuelleRoute->routenpunkte()->attach($punkt->routenpunkte_id, ['reihenfolge' => $index]);
        }
    }

    return response()->json(['message' => 'Route aktualisiert']);
}
public function routenpunkte() //Zum Anzeigen der Routenpunkte, wenn Route anpassen
{
    return response()->json(Routenpunkte::all());
}
}