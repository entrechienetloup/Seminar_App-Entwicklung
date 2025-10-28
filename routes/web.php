<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    FahrzeugeController,
    AuftraegeController,
    ProfileController,
    LagerplatzController,
    StatsController,
    RoutePunktController,
    RouteController,
    SeedController,
    SzenarioController
};
use App\Models\{Fahrzeuge, Auftraege, Lagerplatz, Routenpunkte};




/*
|--------------------------------------------------------------------------
| Protected routes (must be logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/stats', [StatsController::class, 'index'])
    ->name('stats');


    // Startseite
    Route::get('/', [DashboardController::class, 'index'])
          ->name('home');   //

    Route::redirect('/', '/dashboard');

    // Route für die Dashboard-Seite (Hauptseite), lädt alle Fahrzeuge und Aufträge
    Route::get('/dashboard', function () {
        $fahrzeuge = Fahrzeuge::all();
        $auftraege = Auftraege::all();
        return view('pages.index', compact('fahrzeuge', 'auftraege'));
    })->name('dashboard');

    /*
    |------------------------------------------------------------------
    | Fahrzeuge
    |------------------------------------------------------------------
    */
    /*Route::get('/fahrzeug/{id}', function ($id) {
        $fahrzeug = Fahrzeuge::find($id);
        abort_unless($fahrzeug, 404, 'Fahrzeug nicht gefunden');
        return $fahrzeug;          // JSON
    })->name('fahrzeug.show'); */
    
    Route::get('/fahrzeugsliste-daten', [FahrzeugeController::class, 'liste']); //fetch Route 

    // Route für die Fahrzeugliste-Seite, lädt alle Fahrzeuge.
    Route::get('/fahrzeuge', function () {
        $fahrzeuge = Fahrzeuge::all();
        return view('pages.fahrzeugliste', compact('fahrzeuge'));
    })->name('fahrzeuge');

    Route::post('/fahrzeug/{id}/umschalten', [FahrzeugeController::class, 'umschalten'])->name('fahrzeug.umschalten');
    Route::delete('/fahrzeug/{id}/delete',[FahrzeugeController::class, 'delete'])->name('fahrzeug.delete');
    Route::post('/fahrzeug/{id}/fahren-stoppen', [FahrzeugeController::class, 'fahrenStoppen'])->name('fahrzeug.fahrenStoppen');
    Route::post('/fahrzeuge/{fahrzeug}/updateLadestand',
        [FahrzeugeController::class, 'updateLadestand'])->name('fahrzeug.updateLadestand');

    /*
    |------------------------------------------------------------------
    | Aufträge
    |------------------------------------------------------------------
    */
    Route::get('auftraege', [AuftraegeController::class, 'index']);
    /*Route::get('/auftraege/{id}', function ($id) {
        $auftrag = Auftraege::find($id);
        abort_unless($auftrag, 404, 'Auftrag nicht gefunden');
        return $auftrag;
    })->name('auftrag.show');*/

    // Route für die Fahrzeugliste-Seite, lädt alle Aufträge.
    Route::get('/auftraege', function () {
        $auftraege = Auftraege::all();
        return view('pages.auftragsliste', compact('auftraege'));
    })->name('auftraege');

    Route::post('/auftraege/{id}/priorisieren', [AuftraegeController::class, 'priorisieren']);
    
    Route::get('/api/auftraege', [AuftraegeController::class, 'index']);
    Route::put   ('/api/auftraege/{id}', [AuftraegeController::class, 'updateJSON']);
    Route::delete('/api/auftraege/{id}', [AuftraegeController::class, 'deleteJSON']);

    /*
    |------------------------------------------------------------------
    | User profile
    |------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'show']) 
        ->name('profile.show');

    Route::post('/switch-role', function (Illuminate\Http\Request $request) {
    $request->validate([
        'role' => ['required', 'in:leitung,techniker,mitarbeiter'],
    ]);

    $user = $request->user();
    $user->role = $request->role;
    $user->save();

    return back();
    })->name('role.switch')->middleware('auth');




Route::get('/notifications/fahrzeuge', [App\Http\Controllers\FahrzeugeController::class, 'lowBatteryNotifications'])
     ->name('notifications.fahrzeuge');



});




/*
|--------------------------------------------------------------------------
| KARTE
|--------------------------------------------------------------------------
*/

Route::get('/bearbeiten', [FahrzeugeController::class, 'bearbeitenForm']); //Route für die Oberfläche zum bearbeiten
Route::post('/bearbeiten', [FahrzeugeController::class, 'fahrzeugBearbeiten']); //Route Fahrzeug bearbeiten
Route::get('/fahrzeuge-daten', [FahrzeugeController::class, 'index']); //fetch Route für Echtzeitaktualisierung
Route::post('/fahrzeug-anlegen', [FahrzeugeController::class, 'fahrzeugAnlegen']); //Route Fahrzeug anlegen
Route::post('/fahrzeug-bearbeiten', [FahrzeugeController::class, 'fahrzeugBearbeiten']); //Route Fahrzeug bearbeiten
Route::post('/auftrag-anlegen', [FahrzeugeController::class, 'auftragAnlegen']); //Route Fahrzeug anlegen
Route::post('/auftrag-bearbeiten', [FahrzeugeController::class, 'auftragBearbeiten']); //Route Fahrzeug bearbeiten
Route::post('/lagerplatz-anlegen', [FahrzeugeController::class, 'lagerplatzAnlegen']);//Route Lagerplatz anlegen
Route::post('/lagerplatz-bearbeiten', [FahrzeugeController::class, 'lagerplatzBearbeiten']); //Route Lagerplatz bearbeiten
Route::post('/fahrzeuge/{fahrzeug}/position', [FahrzeugeController::class, 'updatePosition']);

Route::post('/lagerplatz/{id}/update-position', [LagerplatzController::class, 'updatePosition']); //Lagerplatz position Updaten
Route::get('/lagerplaetze', [LagerplatzController::class, 'index']); //Lagerplätze auf der Karte laden
Route::get('/lagerplaetze-daten', [LagerplatzController::class, 'index']); //fetch Route für Lagerplätze

Route::get('/seed-daten', [SeedController::class, 'seed'])->name('daten.seed'); //Daten laden für Szenario
Route::get('/seed/auftraege', [SeedController::class, 'auftraege'])->name('seed.auftraege');//Daten nachladen für Szenario


Route::get('/route/{fahrzeugId}', [RouteController::class, 'zeigeRoute']);//onclick die Routen der Fahrzeuge anzeigen
Route::post('/routenpunkt', [RoutePunktController::class, 'store'])->name('routenpunkt.store');
Route::post('/route', [RouteController::class, 'store'])->name('route.store');

Route::get('/routenpunkte', [RouteController::class, 'routenpunkte']); //Routenpunkte laden
Route::post('/route/store', [RouteController::class, 'store']); //Route speichern


Route::get('/szenario/routen', [SzenarioController::class, 'getAlleRouten']);// Snezario starten starten
Route::get('/szenario/routenzwei', [SzenarioController::class, 'getAlleRoutenzwei']);// Szenario Fortsetzen starten
Route::post('/fahrzeug/update-position', [SzenarioController::class, 'updatePosition']); //Postion updaten
Route::post('/fahrzeug/status', [FahrzeugeController::class, 'fahrzeugBearbeiten']); //Status des Fahrzeuges neu setzen


Route::get('/bearbeiten', function () { //für bearbeiten Form
    $lagerplaetze = Lagerplatz::all();
    $fahrzeuge = Fahrzeuge::all();
    $auftraege = Auftraege::all();
    $routenpunkte = Routenpunkte::all();

    return view('pages.bearbeiten', compact('lagerplaetze', 'fahrzeuge', 'auftraege', 'routenpunkte'));
})->name('bearbeiten');
/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (login, register, password reset, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
