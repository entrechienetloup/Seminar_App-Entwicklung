<?php


namespace App\Http\Controllers;

use App\Models\Auftraege;
use App\Models\Lagerplatz;
use App\Models\Routen;
use App\Models\Routenpunkte;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Fahrzeuge;
use Illuminate\Support\Facades\Artisan;


class FahrzeugeController extends Controller
{
    public function index(): JsonResponse //Zum Laden der Fahrzeugdaten
    {
        return response()->json(Fahrzeuge::all());
    }
     public function updatePosition(Request $request, $fahrzeugId) //Bearbeiten-Form zum testen der Fahrzeuge
    {
        $fahrzeug = Fahrzeuge::findOrFail($fahrzeugId);

        $validated = $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        $fahrzeug->x = $validated['x'];
        $fahrzeug->y = $validated['y'];
        $fahrzeug->save();

        return response()->json(['success' => true]);
    }
    public function bearbeitenForm() //Zugriff auf Datenbanken
    {
    $fahrzeuge = Fahrzeuge::all();
    $lagerplaetze = Lagerplatz::all();

    return view('bearbeiten', [
        'fahrzeuge' => $fahrzeuge,
        'lagerplaetze' => $lagerplaetze,
    ]);
    }

    public function route()
    {
        return $this->hasOne(Routen::class, 'fahrzeug_id');
    }

    public function fahrzeugAnlegen(Request $request) //Fahrzeuge in bearbtein Form anlegen
    {
    $validated = $request->validate([
        'fahrzeug_id' => 'required|string',
        'type' => 'required|string',
        'status' => 'required|string',
        'zeitstempel' => 'nullable|string',
        'ladestand' => 'nullable|string',
        'akkuzustand' => 'nullable|string',
        'akt_ta' => 'nullable|string',
        'x' => 'required|integer',
        'y' => 'required|integer',
    ]);

    Fahrzeuge::create($validated);

    return redirect()->back()->with('success', 'Fahrzeug angelegt.');
}

  public function fahrzeugBearbeiten(Request $request) //Fahrzeug in Bearbeiten Form bearbeiten
{
    $request->validate([
        'fahrzeug_id' => 'required|exists:fahrzeuge,fahrzeug_id',
        'meldung' => 'required|string',
        'status' => 'required|string',
    ]);

    \Log::info('Fahrzeug ID im Request: ' . $request->fahrzeug_id);
    $fahrzeug = Fahrzeuge::find($request->fahrzeug_id);

    if (!$fahrzeug) {
        \Log::error('Fahrzeug nicht gefunden!');
        return redirect()->back()->withErrors('Fahrzeug nicht gefunden');
    }

    $fahrzeug->meldung = $request->meldung;
    $fahrzeug->status = $request->status;
    $success = $fahrzeug->save();

    \Log::info('Update Erfolg: ' . ($success ? 'ja' : 'nein'));

    return redirect('/bearbeiten')->with('success', 'Fahrzeug aktualisiert!');
}

public function auftragAnlegen(Request $request)//Funktion Lagerplatz anlegen Bearbeiten Form
    {
        Auftraege::create($request->only(['transportauftrag_id', 'status', 'startort_id', 'zielort_id', 'fahrzeug_id']));
        return redirect()->back()->with('success', 'Auftrag angelegt.');
    }


public function auftragBearbeiten(Request $request)//Funktion Lagerplatz bearbeiten Bearbeiten Form
    {
        $at = Auftraege::findOrFail($request->transportauftrag_id);
        $at = Auftraege::findOrFail($request->transportauftrag_id);
        $at->startort_id = $request->startort_id;
        $at->zielort_id = $request->zielort_id ;
        $at->fahrzeug_id = $request->fahrzeug_id;

        $at->save();

        return redirect()->back()->with('success', 'Auftrag aktualisiert.');
    }
    public function lagerplatzAnlegen(Request $request)//Funktion Lagerplatz anlegen Bearbeiten Form 
    {
        Lagerplatz::create($request->only(['lagerplatz_id','type', 'x', 'y', 'anzahl', 'belegt']));
        return redirect()->back()->with('success', 'Lagerplatz angelegt.');
    }


    public function lagerplatzBearbeiten(Request $request)//Funktion Lagerplatz bearbeiten Bearbeiten Form
    {
        $lp = Lagerplatz::findOrFail($request->lagerplatz_id);
        $lp = Lagerplatz::findOrFail($request->lagerplatz_id);
        $lp->type = $request->type;
        $lp->anzahl = $request->anzahl;
        $lp->belegt = $request->belegt;
        $lp->save();

        return redirect()->back()->with('success', 'Lagerplatz aktualisiert.');
    }

    public function liste(Request $request)
    {
        $fahrzeuge = Fahrzeuge::all();
        return view('pages.fahrzeugliste', [
            'fahrzeuge' => Fahrzeuge::all()
        ]);
    }

    public function umschalten($fahrzeug_id) {
        $fahrzeug = Fahrzeuge::findOrFail($fahrzeug_id);
        if ($fahrzeug->status === 'inaktiv') {
            $fahrzeug->status = 'aktiv';
        } else {
            $fahrzeug->status = 'inaktiv';
        }
        $fahrzeug->save();

        return back()->with('info', 'Fahrzeug neu gestartet/gestoppt.');
    }

    public function delete($fahrzeug_id) {
        $fahrzeug = Fahrzeuge::findOrFail($fahrzeug_id);
        $fahrzeug->delete();

        return back()->with('info', 'Fahrzeug gelöscht.');
    }

    public function fahrenStoppen(Request $request, $fahrzeug_id) {
        $fahrzeug = Fahrzeuge::findOrFail($fahrzeug_id);

        $akku = (int) filter_var($fahrzeug->akkuzustand, FILTER_SANITIZE_NUMBER_INT);

        if ($fahrzeug->status === 'inaktiv') {
            if ($akku === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akkuzustand ist leer! Fahrzeug kann nicht gestartet werden.'
                ]);
            }
            $fahrzeug->status = 'aktiv';
        } else {
            $fahrzeug->status = 'inaktiv';
        }
        $fahrzeug->save();

        return response()->json([
            'success' => true,
            'status' => $fahrzeug->status,
            'akkuzustand' => $fahrzeug->akkuzustand
        ]);
    }

    //Gibt aktuelle Fahrzeug-Benachrichtigungen als JSON zurück
    public function lowBatteryNotifications(): JsonResponse
    {
        Artisan::call('check:fahrzeug-meldungen'); // neue Meldungen in die Meldung table hinzufügen

        
        $notifications = Fahrzeuge::all()->map(function ($fahrzeug) {
            return $fahrzeug->getNotification();
        })->filter()->values(); // enternet leere Benachrichtigungen (null)

        return response()->json([
            'notifications' => $notifications
        ]);
    }

}


