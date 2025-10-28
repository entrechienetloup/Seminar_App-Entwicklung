<?php

namespace App\Http\Controllers;
use App\Models\Auftraege;
use App\Models\Routen;
use App\Models\Routenpunkte;
use App\Models\Fahrzeuge;
use App\Models\Lagerplatz;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Meldung;


class SeedController extends Controller
{
    public function seed(): JsonResponse
{

    // Reihenfolge wichtig 
    Routen::query()->delete();
    Auftraege::query()->delete();
    Fahrzeuge::query()->delete();
    Lagerplatz::query()->delete();
    Routenpunkte::query()->delete();
    // === Fahrzeuge einfügen ===
    $fahrzeuge = [
        ['fahrzeug_id' => 'HS01', 'type' => 'Hochregalstapler', 'status' => 'außer Betrieb', 'zeitstempel' => now(), 'ladestand' => '23%', 'akkuzustand' => '85%', 'akt_ta' => null, 'meldung' => 'Technische Wartung','x'=>'184','y'=>'95'],
        ['fahrzeug_id' => 'HS02', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '19%', 'akkuzustand' => '99%', 'akt_ta' => 'TA1', 'meldung' => 'Keine','x'=>'565','y'=>'565'],
        ['fahrzeug_id' => 'HS03', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '67%', 'akkuzustand' => '87%', 'akt_ta' => 'TA2', 'meldung' => 'Keine','x'=>'565','y'=>'488'],
        ['fahrzeug_id' => 'HS04', 'type' => 'Hochregalstapler', 'status' => 'inaktiv', 'zeitstempel' => now(), 'ladestand' => '80%', 'akkuzustand' => '85%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'229','y'=>'95'],
        ['fahrzeug_id' => 'HS05', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '50%', 'akkuzustand' => '59%', 'akt_ta' => 'TA4', 'meldung' => 'Keine','x'=>'319','y'=>'660'],
        ['fahrzeug_id' => 'HS06', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '21%', 'akkuzustand' => '98%', 'akt_ta' => 'TA5', 'meldung' => 'Keine','x'=>'565','y'=>'189'],
        ['fahrzeug_id' => 'HS07', 'type' => 'Hochregalstapler', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '77%', 'akkuzustand' => '97%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'274','y'=>'95'],
        ['fahrzeug_id' => 'GH01', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '31%', 'akkuzustand' => '85%', 'akt_ta' => 'TA6', 'meldung' => 'Keine','x'=>'1010','y'=>'585'],
        ['fahrzeug_id' => 'GH02', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '64%', 'akkuzustand' => '99%', 'akt_ta' => 'TA7', 'meldung' => 'Keine','x'=>'725','y'=>'488'],
        ['fahrzeug_id' => 'GH03', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '76%', 'akkuzustand' => '87%', 'akt_ta' => 'TA8', 'meldung' => 'Keine','x'=>'1180','y'=>'540'],
        ['fahrzeug_id' => 'GH04', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '17%', 'akkuzustand' => '99%', 'akt_ta' => 'TA3', 'meldung' => 'Keine','x'=>'880','y'=>'450'],
        ['fahrzeug_id' => 'GH05', 'type' => 'Gabelhubwagen', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '67%', 'akkuzustand' => '99%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'94','y'=>'95'],
        ['fahrzeug_id' => 'GH06', 'type' => 'Gabelhubwagen', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '43%', 'akkuzustand' => '87%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'135','y'=>'95'],
    ];
    Fahrzeuge::insert($fahrzeuge);

    $meldungen = [
    ['fahrzeug_id' => 'GH01', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(1)],
    ['fahrzeug_id' => 'GH02', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(2)],
    ['fahrzeug_id' => 'GH03', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(3)],
    ['fahrzeug_id' => 'GH04', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(4)],
    ['fahrzeug_id' => 'GH05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(5)],
    ['fahrzeug_id' => 'GH06', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(6)],
    ['fahrzeug_id' => 'HS01', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(7)],
    ['fahrzeug_id' => 'HS02', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(8)],
    ['fahrzeug_id' => 'HS03', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(9)],
    ['fahrzeug_id' => 'HS04', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(10)],
    ['fahrzeug_id' => 'HS05', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(1)],
    ['fahrzeug_id' => 'HS06', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(2)],
    ['fahrzeug_id' => 'HS07', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(3)],
    ['fahrzeug_id' => 'GH01', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(4)],
    ['fahrzeug_id' => 'GH02', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(5)],
    ['fahrzeug_id' => 'GH03', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(6)],
    ['fahrzeug_id' => 'GH04', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(7)],
    ['fahrzeug_id' => 'GH05', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(8)],
    ['fahrzeug_id' => 'GH06', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(9)],
    ['fahrzeug_id' => 'HS01', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(10)],
    ['fahrzeug_id' => 'HS02', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(1)],
    ['fahrzeug_id' => 'HS03', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(2)],
    ['fahrzeug_id' => 'HS04', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(3)],
    ['fahrzeug_id' => 'HS05', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(4)],
    ['fahrzeug_id' => 'HS06', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(5)],
    ['fahrzeug_id' => 'HS07', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(6)],
    ['fahrzeug_id' => 'GH01', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(7)],
    ['fahrzeug_id' => 'GH02', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(8)],
    ['fahrzeug_id' => 'GH03', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(9)],
    ['fahrzeug_id' => 'GH04', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(10)],
    ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(3)],
    ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(2)],
    ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(7)],
    ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(13)],
];

foreach ($meldungen as &$m) {
    $m['created_at'] = now();
    $m['updated_at'] = now();
}

Meldung::insert($meldungen);


    // === Lagerplätze generieren ===
            $lagerplaetze = [
            [
                'lagerplatz_id' => 'BP-1',
                'type' => 'BP',
                'x' => 586,
                'y' => 543,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'BP-2',
                'type' => 'BP',
                'x' => 668,
                'y' => 543,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'BP-3',
                'type' => 'BP',
                'x' => 586,
                'y' => 471,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'BP-4',
                'type' => 'BP',
                'x' => 668,
                'y' => 471,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'BP-5',
                'type' => 'BP',
                'x' => 586,
                'y' => 244,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'BP-6',
                'type' => 'BP',
                'x' => 668,
                'y' => 244,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'BP-7',
                'type' => 'BP',
                'x' => 586,
                'y' => 172,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'BP-8',
                'type' => 'BP',
                'x' => 668,
                'y' => 172,
                'anzahl' => 1,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-1',
                'type' => 'HR',
                'x' => 390,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-2',
                'type' => 'HR',
                'x' => 345,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-3',
                'type' => 'HR',
                'x' => 300,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-4',
                'type' => 'HR',
                'x' => 255,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-5',
                'type' => 'HR',
                'x' => 210,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-6',
                'type' => 'HR',
                'x' => 165,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-7',
                'type' => 'HR',
                'x' => 120,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-1-8',
                'type' => 'HR',
                'x' => 75,
                'y' => 674,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-1',
                'type' => 'HR',
                'x' => 390,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-2',
                'type' => 'HR',
                'x' => 345,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-3',
                'type' => 'HR',
                'x' => 300,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-4',
                'type' => 'HR',
                'x' => 255,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-5',
                'type' => 'HR',
                'x' => 210,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-6',
                'type' => 'HR',
                'x' => 165,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-7',
                'type' => 'HR',
                'x' => 120,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-2-8',
                'type' => 'HR',
                'x' => 75,
                'y' => 570,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-1',
                'type' => 'HR',
                'x' => 390,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-2',
                'type' => 'HR',
                'x' => 345,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-3',
                'type' => 'HR',
                'x' => 300,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-4',
                'type' => 'HR',
                'x' => 255,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-5',
                'type' => 'HR',
                'x' => 210,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-6',
                'type' => 'HR',
                'x' => 165,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-7',
                'type' => 'HR',
                'x' => 120,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-3-8',
                'type' => 'HR',
                'x' => 75,
                'y' => 532,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-1',
                'type' => 'HR',
                'x' => 390,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-2',
                'type' => 'HR',
                'x' => 345,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-3',
                'type' => 'HR',
                'x' => 300,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-4',
                'type' => 'HR',
                'x' => 255,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-5',
                'type' => 'HR',
                'x' => 210,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-6',
                'type' => 'HR',
                'x' => 165,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-7',
                'type' => 'HR',
                'x' => 120,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-4-8',
                'type' => 'HR',
                'x' => 75,
                'y' => 426,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-1',
                'type' => 'HR',
                'x' => 390,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-2',
                'type' => 'HR',
                'x' => 345,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-3',
                'type' => 'HR',
                'x' => 300,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-4',
                'type' => 'HR',
                'x' => 255,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-5',
                'type' => 'HR',
                'x' => 210,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-6',
                'type' => 'HR',
                'x' => 165,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-7',
                'type' => 'HR',
                'x' => 120,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-5-8',
                'type' => 'HR',
                'x' => 75,
                'y' => 385,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-1',
                'type' => 'HR',
                'x' => 390,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-2',
                'type' => 'HR',
                'x' => 345,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-3',
                'type' => 'HR',
                'x' => 300,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-4',
                'type' => 'HR',
                'x' => 255,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-5',
                'type' => 'HR',
                'x' => 210,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-6',
                'type' => 'HR',
                'x' => 165,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-7',
                'type' => 'HR',
                'x' => 120,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-6-8',
                'type' => 'HR',
                'x' => 75,
                'y' => 279,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-1',
                'type' => 'HR',
                'x' => 390,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-2',
                'type' => 'HR',
                'x' => 345,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-3',
                'type' => 'HR',
                'x' => 300,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-4',
                'type' => 'HR',
                'x' => 255,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-5',
                'type' => 'HR',
                'x' => 210,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-6',
                'type' => 'HR',
                'x' => 165,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-7',
                'type' => 'HR',
                'x' => 120,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'HR-7-8',
                'type' => 'HR',
                'x' => 75,
                'y' => 239,
                'anzahl' => 9,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-1-1',
                'type' => 'WA',
                'x' => 906,
                'y' => 291,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-1-2',
                'type' => 'WA',
                'x' => 906,
                'y' => 247,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-1-3',
                'type' => 'WA',
                'x' => 906,
                'y' => 203,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-1-4',
                'type' => 'WA',
                'x' => 906,
                'y' => 159,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-2-1',
                'type' => 'WA',
                'x' => 950,
                'y' => 291,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-2-2',
                'type' => 'WA',
                'x' => 950,
                'y' => 247,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-2-3',
                'type' => 'WA',
                'x' => 950,
                'y' => 203,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-2-4',
                'type' => 'WA',
                'x' => 950,
                'y' => 159,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-3-1',
                'type' => 'WA',
                'x' => 1080,
                'y' => 291,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-3-2',
                'type' => 'WA',
                'x' => 1080,
                'y' => 247,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-3-3',
                'type' => 'WA',
                'x' => 1080,
                'y' => 203,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-3-4',
                'type' => 'WA',
                'x' => 1080,
                'y' => 159,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-4-1',
                'type' => 'WA',
                'x' => 1123,
                'y' => 291,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-4-2',
                'type' => 'WA',
                'x' => 1123,
                'y' => 247,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-4-3',
                'type' => 'WA',
                'x' => 1123,
                'y' => 203,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WA-4-4',
                'type' => 'WA',
                'x' => 1123,
                'y' => 159,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-1-4',
                'type' => 'WE',
                'x' => 900,
                'y' => 567,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-1-3',
                'type' => 'WE',
                'x' => 900,
                'y' => 523,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-1-2',
                'type' => 'WE',
                'x' => 900,
                'y' => 479,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-1-1',
                'type' => 'WE',
                'x' => 900,
                'y' => 435,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-2-4',
                'type' => 'WE',
                'x' => 945,
                'y' => 567,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-2-3',
                'type' => 'WE',
                'x' => 945,
                'y' => 523,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-2-2',
                'type' => 'WE',
                'x' => 945,
                'y' => 479,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-2-1',
                'type' => 'WE',
                'x' => 945,
                'y' => 435,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-3-4',
                'type' => 'WE',
                'x' => 1075,
                'y' => 567,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-3-3',
                'type' => 'WE',
                'x' => 1075,
                'y' => 523,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-3-2',
                'type' => 'WE',
                'x' => 1075,
                'y' => 479,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-3-1',
                'type' => 'WE',
                'x' => 1075,
                'y' => 435,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-4-4',
                'type' => 'WE',
                'x' => 1119,
                'y' => 567,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-4-3',
                'type' => 'WE',
                'x' => 1119,
                'y' => 523,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-4-2',
                'type' => 'WE',
                'x' => 1119,
                'y' => 479,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WE-4-1',
                'type' => 'WE',
                'x' => 1119,
                'y' => 435,
                'anzahl' => 2,
                'belegt' => 0
            ],
            [
                'lagerplatz_id' => 'WB-5', //dummy für Szenario
                'type' => 'unsichtbar',
                'x' => 229,
                'y' => 95,
                'anzahl' => 0,
                'belegt' => 0
            ],
        ];

        foreach ($lagerplaetze as &$lp) {
            $lp['created_at'] = Carbon::now();
            $lp['updated_at'] = Carbon::now();
        }
        //Lagerplätze einfügen
        Lagerplatz::upsert(
            $lagerplaetze,
            ['lagerplatz_id'], 
            ['type', 'x', 'y', 'anzahl', 'belegt', 'updated_at']
        );
        
    // === Aufträge einfügen ===
    $auftraege = [
        ['transportauftrag_id' => 'TA1', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-1', 'zielort_id' => 'HR-6-5', 'fahrzeug_id' => 'HS02'],
        ['transportauftrag_id' => 'TA2', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-2', 'zielort_id' => 'HR-1-4', 'fahrzeug_id' => 'HS03'],
        ['transportauftrag_id' => 'TA3', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-1-1', 'zielort_id' => 'BP-6', 'fahrzeug_id' => 'GH04'],
        ['transportauftrag_id' => 'TA4', 'status' => 'wird ausgeführt', 'startort_id' => 'HR-1-3', 'zielort_id' => 'BP-8', 'fahrzeug_id' => 'HS05'],
        ['transportauftrag_id' => 'TA5', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-8', 'zielort_id' => 'HR-7-4', 'fahrzeug_id' => 'HS06'],
        ['transportauftrag_id' => 'TA6', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-2-4', 'zielort_id' => 'BP-1', 'fahrzeug_id' => 'GH01'],
        ['transportauftrag_id' => 'TA7', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-4', 'zielort_id' => 'WA-4-2', 'fahrzeug_id' => 'GH02'],
        ['transportauftrag_id' => 'TA8', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-4-3', 'zielort_id' => 'WA-3-1', 'fahrzeug_id' => 'GH03'],
        ['transportauftrag_id' => 'TA9', 'status' => 'wartet', 'startort_id' => 'BP-3', 'zielort' => 'WA-1-1', 'fahrzeug_id' => 'GH04'],
        ['transportauftrag_id' => 'TA10', 'status' => 'wartet', 'startort_id' => 'HR-7-8', 'zielort_id' => 'BP-2', 'fahrzeug_id' => 'HS02'],
        ['transportauftrag_id' => 'TA11', 'status' => 'wartet', 'startort_id' => 'BP-6', 'zielort_id' => 'HR-5-3', 'fahrzeug_id' => 'HS03'],
        ['transportauftrag_id' => 'TA12', 'status' => 'wartet', 'startort_id' => 'BP-5', 'zielort_id' => 'WA-2-4', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA13', 'status' => 'wartet', 'startort_id' => 'BP-7', 'zielort_id' => 'WA-1-4', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA14', 'status' => 'wartet', 'startort_id' => 'BP-2', 'zielort_id' => 'HR-7-3', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA15', 'status' => 'wartet', 'startort_id' => 'WE-3-3', 'zielort_id' => 'WA-1-2', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA16', 'status' => 'wartet', 'startort_id' => 'WE-2-2', 'zielort_id' => 'BP-1', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA17', 'status' => 'wartet', 'startort_id' => 'BP-5', 'zielort_id' => 'HR-7-5', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA18', 'status' => 'wartet', 'startort_id' => 'HR-6-5', 'zielort_id' => 'BP-5', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA19', 'status' => 'wartet', 'startort_id' => 'HR-2-5', 'zielort_id' => 'BP-6', 'fahrzeug_id' => 'HSX'],
    ];
    foreach ($auftraege as &$a) {
        $a['created_at'] = $a['updated_at'] = now();
    }
    Auftraege::insert($auftraege);

    $punkte = [

    //Wartungsbereich 
    "95:409" => "WB-1", "95:364" => "WB-2", "95:319" => "WB-3", "95:274" => "WB-4", "95:229" => "WB-5", "95:184" => "WB-6", "95:139" => "WB-7", "95:94" => "WB-8",

    // HR-Reihe 1–7 
    "660:409" => "HR-1-1", "660:364" => "HR-1-2", "660:319" => "HR-1-3", "660:274" => "HR-1-4", "660:229" => "HR-1-5", "660:184" => "HR-1-6", "660:139" => "HR-1-7", "660:94" => "HR-1-8",
    "620:409" => "HR-2-1", "620:364" => "HR-2-2", "620:319" => "HR-2-3", "620:274" => "HR-2-4", "620:229" => "HR-2-5", "620:184" => "HR-2-6", "620:139" => "HR-2-7", "620:94" => "HR-2-8",
    "518:409" => "HR-3-1", "518:364" => "HR-3-2", "518:319" => "HR-3-3", "518:274" => "HR-3-4", "518:229" => "HR-3-5", "518:184" => "HR-3-6", "518:139" => "HR-3-7", "518:94" => "HR-3-8",
    "478:409" => "HR-4-1", "478:364" => "HR-4-2", "478:319" => "HR-4-3", "478:274" => "HR-4-4", "478:229" => "HR-4-5", "478:184" => "HR-4-6", "478:139" => "HR-4-7", "478:94" => "HR-4-8",
    "371:409" => "HR-5-1", "371:364" => "HR-5-2", "371:319" => "HR-5-3", "371:274" => "HR-5-4", "371:229" => "HR-5-5", "371:184" => "HR-5-6", "371:139" => "HR-5-7", "371:94" => "HR-5-8",
    "331:409" => "HR-6-1", "331:364" => "HR-6-2", "331:319" => "HR-6-3", "331:274" => "HR-6-4", "331:229" => "HR-6-5", "331:184" => "HR-6-6", "331:139" => "HR-6-7", "331:94" => "HR-6-8",
    "225:409" => "HR-7-1", "225:364" => "HR-7-2", "225:319" => "HR-7-3", "225:274" => "HR-7-4", "225:229" => "HR-7-5", "225:184" => "HR-7-6", "225:139" => "HR-7-7", "225:94" => "HR-7-8",

    // WBG
    "189:409" => "WBG-1", "189:364" => "WBG-2", "189:319" => "WBG-3", "189:274" => "WBG-4", "189:229" => "WBG-5", "189:184" => "WBG-6", "189:139" => "WBG-7", "189:94" => "WBG-8",

    // GA 
    "660:500" => "GA-1", "620:500" => "GA-2", "518:500" => "GA-3", "478:500" => "GA-4", "371:500" => "GA-5", "331:500" => "GA-6", "225:500" => "GA-7", 
    "95:500" => "GA-L-UNTEN", 
    "660: 785"=> "GA-R-OBEN", "345:785" => "GA-R-MITTE-U",  "95:785" => "GA-R-UNTEN", "405:785" => "GA-R-MITTE-O",

    // BP Links
    "560.5:565" => "BP-1-L", "488.5:565" => "BP-3-L", "261.5:565" => "BP-5-L", "189.5:565" => "BP-7-L",

    // BP GAL
    "560.5:500" => "BP-1-GA-L", "488.5:500" => "BP-3-GA-L", "261.5:500" => "BP-5-GA-L", "189.5:500" => "BP-7-GA-L",

    // BP Rechts
    "560.5:725" => "BP-2-R", "488.5:725" => "BP-4-R", "261.5:725" => "BP-6-R", "189.5:725" => "BP-8-R",

    // BP GAR
   "560.5:785" => "BP-2-GA-R", "488.5:785" => "BP-4-GA-R", "261.5:785" => "BP-6-GA-R", "189.5:785" => "BP-8-GA-R", 

    // BP Oben / Unten
    "596:605" => "BP-1-O", "596:687" => "BP-2-O", "155:605" => "BP-7-U", "155:687" => "BP-8-U",

    // BP Oben / Unten 
    "596:500" => "BP-1-O-GA-L", "660:605" => "BP-1-O-GA-O", "596:785" => "BP-2-O-GA-R", "660:687" => "BP-2-O-GA-O",
    "155:500" => "BP-7-U-GA-L", "95:605" => "BP-7-U-GA-U", "155:785" => "BP-8-U-GA-R", "95:687" => "BP-8-U-GA-U",

    // Zugänge zu Warenein-/-ausgang
    "345:880" => "WA-1", "345:1010" => "WA-2", "345:1057" => "WA-3", "345:1180" => "WA-4",
    "405:880" => "WE-1", "405:1010" => "WE-2", "405:1057" => "WE-3", "405:1180" => "WE-4",

    // WA-Reihen
    "305:880" => "WA-1-1", "260:880" => "WA-1-2", "215:880" => "WA-1-3", "170:880" => "WA-1-4",
    "305:1010" => "WA-2-1", "260:1010" => "WA-2-2", "215:1010" => "WA-2-3", "170:1010" => "WA-2-4",
    "305:1057" => "WA-3-1", "260:1057" => "WA-3-2", "215:1057" => "WA-3-3", "170:1057" => "WA-3-4",
    "305:1180" => "WA-4-1", "260:1180" => "WA-4-2", "215:1180" => "WA-4-3", "170:1180" => "WA-4-4",

    // WE-Reihen
    "450:880" => "WE-1-1", "495:880" => "WE-1-2", "540:880" => "WE-1-3", "585:880" => "WE-1-4",
    "450:1010" => "WE-2-1", "495:1010" => "WE-2-2", "540:1010" => "WE-2-3", "585:1010" => "WE-2-4",
    "450:1057" => "WE-3-1", "495:1057" => "WE-3-2", "540:1057" => "WE-3-3", "585:1057" => "WE-3-4",
    "450:1180" => "WE-4-1", "495:1180" => "WE-4-2", "540:1180" => "WE-4-3", "585:1180" => "WE-4-4",
];

    $eintraege = collect($punkte)->map(function ($routenpunkte_id, $koordinate) {
        [$y, $x] = array_map('trim', explode(':', $koordinate)); // Format: y:x

        return [
            'routenpunkte_id' => $routenpunkte_id, // ← hier korrigiert!
            'x'          => (int)$x,
            'y'          => (int)$y,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    })->toArray();

    // Einfügen in die Tabelle routenpunkte
    Routenpunkte::insert($eintraege);

    $routen = [
        [
            'fahrzeug_id' => 'HS03',
            'startort_id' => 'BP-3',
            'zielort_id' => 'HR-5-7',
            'routenpunkte' => ['BP-3-L', 'BP-3-GA-L', 'GA-5','HR-5-7']
        ],
        [
            'fahrzeug_id' => 'GH01',
            'startort_id' => 'WE-2-4',
            'zielort_id' => 'BP-7',
            'routenpunkte' => ['WE-2-4', 'WE-2','WA-2', 'GA-R-MITTE-U', 'BP-8-U-GA-R','BP-8-U','BP-7-U']
        ],
        [
            'fahrzeug_id' => 'HS02',
            'startort_id' => 'BP-1',
            'zielort_id' => 'HR-4-5',
            'routenpunkte' => ['BP-1-L', 'BP-1-GA-L', 'GA-4', 'HR-4-5']
        ],
        [
            'fahrzeug_id' => 'GH04',
            'startort_id' => 'WE-1-1',
            'zielort_id' => 'BP-6',
            'routenpunkte' => ['WE-1-1', 'WE-1','WA-1', 'GA-R-MITTE-U','BP-6-GA-R','BP-6-R']
        ],
        [
            'fahrzeug_id' => 'HS05',
            'startort_id' => 'HR-1-3',
            'zielort_id' => 'BP-8',
            'routenpunkte' => ['HR-1-3', 'GA-1', 'GA-L-UNTEN','BP-8-U-GA-U','BP-8-U']
        ],
        [
            'fahrzeug_id' => 'HS06',
            'startort_id' => 'BP-7',
            'zielort_id' => 'HR-5-6',
            'routenpunkte' => ['BP-7-L', 'BP-7-GA-L', 'GA-5', 'HR-5-6']
        ],
        [
            'fahrzeug_id' => 'GH02',
            'startort_id' => 'BP-4',
            'zielort_id' => 'WE-4-2',
            'routenpunkte' => ['BP-4-R', 'BP-4-GA-R', 'GA-R-MITTE-O', 'WE-4', 'WE-4-2']
        ],
        [
            'fahrzeug_id' => 'GH03',
            'startort_id' => 'WE-4-3',
            'zielort_id' => 'WA-3-1',
            'routenpunkte' => ['WE-4-3', 'WE-4', 'WA-4','WA-3','WA-3-1']
        ],
        
    ];

    foreach ($routen as $route) {
        DB::transaction(function () use ($route) {
            $r = Routen::create([
                'fahrzeug_id' => $route['fahrzeug_id'],
                'startort_id' => $route['startort_id'],
                'zielort_id' => $route['zielort_id'],
            ]);

            foreach ($route['routenpunkte'] as $index => $rp) {
                $punkt = Routenpunkte::where('routenpunkte_id', $rp)->first();
                if ($punkt) {
                    $r->routenpunkte()->attach($punkt->routenpunkte_id, ['reihenfolge' => $index]);
                }
            }
        });
    }
        
        return response()->json(['message' => 'Alle Daten erfolgreich eingefügt.']);
    }
public function auftraege(){ //zweiter Teil Szenario Präsentation
    Routen::query()->delete();
    Auftraege::query()->delete();
    Fahrzeuge::query()->delete();
    // === Fahrzeuge einfügen ===
    $fahrzeuge = [
        ['fahrzeug_id' => 'HS01', 'type' => 'Hochregalstapler', 'status' => 'außer Betrieb', 'zeitstempel' => now(), 'ladestand' => '23%', 'akkuzustand' => '85%', 'akt_ta' => null, 'meldung' => 'Technische Wartung','x'=>'184','y'=>'95'],
        ['fahrzeug_id' => 'HS02', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '19%', 'akkuzustand' => '99%', 'akt_ta' => 'TA1', 'meldung' => 'Keine','x'=>'229','y'=>'478'],
        ['fahrzeug_id' => 'HS03', 'type' => 'Hochregalstapler', 'status' => 'nicht erreichbar', 'zeitstempel' => now(), 'ladestand' => '67%', 'akkuzustand' => '87%', 'akt_ta' => null, 'meldung' => 'Nicht Erreichbar','x'=>'319','y'=>'371'],
        ['fahrzeug_id' => 'HS04', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '80%', 'akkuzustand' => '85%', 'akt_ta' => 'TA2', 'meldung' => 'Keine','x'=>'229','y'=>'95'],
        ['fahrzeug_id' => 'HS05', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '50%', 'akkuzustand' => '59%', 'akt_ta' => 'TA4', 'meldung' => 'Keine','x'=>'687','y'=>'155'],
        ['fahrzeug_id' => 'HS06', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '21%', 'akkuzustand' => '98%', 'akt_ta' => 'TA5', 'meldung' => 'Keine','x'=>'364','y'=>'371'],
        ['fahrzeug_id' => 'HS07', 'type' => 'Hochregalstapler', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '77%', 'akkuzustand' => '97%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'274','y'=>'95'],
        ['fahrzeug_id' => 'GH01', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '31%', 'akkuzustand' => '85%', 'akt_ta' => 'TA6', 'meldung' => 'Keine','x'=>'605','y'=>'155'],
        ['fahrzeug_id' => 'GH02', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '64%', 'akkuzustand' => '99%', 'akt_ta' => 'TA7', 'meldung' => 'Keine','x' => '1180', 'y' => '495'],
        ['fahrzeug_id' => 'GH03', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '76%', 'akkuzustand' => '87%', 'akt_ta' => 'TA8', 'meldung' => 'Keine','x'=>'1057','y'=>'305'],
        ['fahrzeug_id' => 'GH04', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '17%', 'akkuzustand' => '99%', 'akt_ta' => 'TA3', 'meldung' => 'Keine','x'=>'725','y'=>'261'],
        ['fahrzeug_id' => 'GH05', 'type' => 'Gabelhubwagen', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '67%', 'akkuzustand' => '99%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'94','y'=>'95'],
        ['fahrzeug_id' => 'GH06', 'type' => 'Gabelhubwagen', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '43%', 'akkuzustand' => '87%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'135','y'=>'95'],
    ];
    
    Fahrzeuge::insert($fahrzeuge);

    // === Aufträge einfügen ===
    $auftraege = [
        ['transportauftrag_id' => 'TA1', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-1', 'zielort_id' => 'HR-6-5', 'fahrzeug_id' => 'HS02'],
        ['transportauftrag_id' => 'TA2', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-2', 'zielort_id' => 'HR-1-4', 'fahrzeug_id' => 'HS04'],
        ['transportauftrag_id' => 'TA3', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-1-1', 'zielort_id' => 'BP-6', 'fahrzeug_id' => 'GH04'],
        ['transportauftrag_id' => 'TA4', 'status' => 'wird ausgeführt', 'startort_id' => 'HR-1-3', 'zielort_id' => 'BP-8', 'fahrzeug_id' => 'HS05'],
        ['transportauftrag_id' => 'TA5', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-8', 'zielort_id' => 'HR-7-4', 'fahrzeug_id' => 'HS06'],
        ['transportauftrag_id' => 'TA6', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-2-4', 'zielort_id' => 'BP-1', 'fahrzeug_id' => 'GH01'],
        ['transportauftrag_id' => 'TA7', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-4', 'zielort_id' => 'WA-4-2', 'fahrzeug_id' => 'GH02'],
        ['transportauftrag_id' => 'TA8', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-4-3', 'zielort_id' => 'WA-3-1', 'fahrzeug_id' => 'GH03'],
        ['transportauftrag_id' => 'TA9', 'status' => 'wartet', 'startort_id' => 'BP-3', 'zielort' => 'WA-1-1', 'fahrzeug_id' => 'GH04'],
        ['transportauftrag_id' => 'TA10', 'status' => 'wartet', 'startort_id' => 'HR-7-8', 'zielort_id' => 'BP-2', 'fahrzeug_id' => 'HS02'],
        ['transportauftrag_id' => 'TA11', 'status' => 'wartet', 'startort_id' => 'BP-6', 'zielort_id' => 'HR-5-3', 'fahrzeug_id' => 'HS03'],
        ['transportauftrag_id' => 'TA12', 'status' => 'wartet', 'startort_id' => 'BP-5', 'zielort_id' => 'WA-2-4', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA13', 'status' => 'wartet', 'startort_id' => 'BP-7', 'zielort_id' => 'WA-1-4', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA14', 'status' => 'wartet', 'startort_id' => 'BP-2', 'zielort_id' => 'HR-7-3', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA15', 'status' => 'wartet', 'startort_id' => 'WE-3-3', 'zielort_id' => 'WA-1-2', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA16', 'status' => 'wartet', 'startort_id' => 'WE-2-2', 'zielort_id' => 'BP-1', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA17', 'status' => 'wartet', 'startort_id' => 'BP-5', 'zielort_id' => 'HR-7-5', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA18', 'status' => 'wartet', 'startort_id' => 'HR-6-5', 'zielort_id' => 'BP-5', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA19', 'status' => 'wartet', 'startort_id' => 'HR-2-5', 'zielort_id' => 'BP-6', 'fahrzeug_id' => 'HSX'],
    ];
    foreach ($auftraege as &$a) {
        $a['created_at'] = $a['updated_at'] = now();
    }
    Auftraege::insert($auftraege);

    $routen = [
        [
            'fahrzeug_id' => 'HS04',
            'startort_id' => 'WB-5',
            'zielort_id' => 'HR-5-7',
            'routenpunkte' => ['WB-5', 'HR-7-5', 'GA-7','GA-6','HR-6-7','HR-5-7']
        ],
        [
            'fahrzeug_id' => 'GH01',
            'startort_id' => 'BP-7',
            'zielort_id' => 'WE-4-3',
            'routenpunkte' => ['BP-7-U', 'BP-8-U','BP-8-U-GA-R', 'GA-R-MITTE-U', 'WA-4','WE-4-3']
        ],
        [
            'fahrzeug_id' => 'HS02',
            'startort_id' => 'HR-4-5',
            'zielort_id' => 'BP-3',
            'routenpunkte' => ['HR-4-5', 'GA-4', 'BP-3-GA-L', 'BP-3-L']
        ],
        [
            'fahrzeug_id' => 'GH04',
            'startort_id' => 'BP-6',
            'zielort_id' => 'WA-4-4',
            'routenpunkte' => ['BP-6-R', 'BP-6-GA-R','GA-R-MITTE-U', 'WA-4','WA-4-4']
        ],
        [
            'fahrzeug_id' => 'HS05',
            'startort_id' => 'BP-8',
            'zielort_id' => 'HR-7-8',
            'routenpunkte' => ['BP-8-U', 'BP-8-U-GA-U', 'GA-L-UNTEN','BP-7-GA-L','WBG-8','HR-7-8']
        ],
        [
            'fahrzeug_id' => 'HS06',
            'startort_id' => 'BP-7',
            'zielort_id' => 'HR-5-6',
            'routenpunkte' => ['BP-7-L', 'BP-7-GA-L', 'GA-5', 'HR-5-2','HR-6-2', 'HR-6-6', 'HR-5-6']
        ],
        [
            'fahrzeug_id' => 'GH02',
            'startort_id' => 'WE-4-2',
            'zielort_id' => 'BP-2',
            'routenpunkte' => ['WE-4-2', 'WE-4', 'GA-R-MITTE-O',  'BP-2-GA-R', 'BP-2-R']
        ],
        [
            'fahrzeug_id' => 'GH03',
            'startort_id' => 'WA-3-1',
            'zielort_id' => 'BP-8',
            'routenpunkte' => ['WA-3-1', 'WE-3', 'GA-R-MITTE-O','BP-8-GA-R','BP-8-R']
        ],
    ];
        foreach ($routen as $route) {
        DB::transaction(function () use ($route) {
            $r = Routen::create([
                'fahrzeug_id' => $route['fahrzeug_id'],
                'startort_id' => $route['startort_id'],
                'zielort_id' => $route['zielort_id'],
            ]);

            foreach ($route['routenpunkte'] as $index => $rp) {
                $punkt = Routenpunkte::where('routenpunkte_id', $rp)->first();
                if ($punkt) {
                    $r->routenpunkte()->attach($punkt->routenpunkte_id, ['reihenfolge' => $index]);
                }
            }
        });
    }
    $meldungen = [
        ['fahrzeug_id' => 'GH01', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(1)],
        ['fahrzeug_id' => 'GH02', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'GH03', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'GH04', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(4)],
        ['fahrzeug_id' => 'GH05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(5)],
        ['fahrzeug_id' => 'GH06', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(6)],
        ['fahrzeug_id' => 'HS01', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'HS02', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(8)],
        ['fahrzeug_id' => 'HS03', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(9)],
        ['fahrzeug_id' => 'HS04', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(10)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(1)],
        ['fahrzeug_id' => 'HS06', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'HS07', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'GH01', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(4)],
        ['fahrzeug_id' => 'GH02', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(5)],
        ['fahrzeug_id' => 'GH03', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(6)],
        ['fahrzeug_id' => 'GH04', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'GH05', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(8)],
        ['fahrzeug_id' => 'GH06', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(9)],
        ['fahrzeug_id' => 'HS01', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(10)],
        ['fahrzeug_id' => 'HS02', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(1)],
        ['fahrzeug_id' => 'HS03', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'HS04', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(4)],
        ['fahrzeug_id' => 'HS06', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(5)],
        ['fahrzeug_id' => 'HS07', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(6)],
        ['fahrzeug_id' => 'GH01', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'GH02', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(8)],
        ['fahrzeug_id' => 'GH03', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(9)],
        ['fahrzeug_id' => 'GH04', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(10)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(13)],
    ];

    foreach ($meldungen as &$m) {
        $m['created_at'] = now();
        $m['updated_at'] = now();
    }

    Meldung::insert($meldungen);
    return redirect()->back()->with('success', 'Daten aktualisiert');
}
public function SzenarioEnde(){ //zweiter Teil Szenario Präsentation
    Routen::query()->delete();
    Auftraege::query()->delete();
    Fahrzeuge::query()->delete();
    // === Fahrzeuge einfügen ===
    $fahrzeuge = [
        ['fahrzeug_id' => 'HS01', 'type' => 'Hochregalstapler', 'status' => 'außer Betrieb', 'zeitstempel' => now(), 'ladestand' => '23%', 'akkuzustand' => '85%', 'akt_ta' => null, 'meldung' => 'Technische Wartung','x'=>'184','y'=>'95'],
        ['fahrzeug_id' => 'HS02', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '19%', 'akkuzustand' => '99%', 'akt_ta' => 'TA1', 'meldung' => 'Keine','x'=>'229','y'=>'478'],
        ['fahrzeug_id' => 'HS03', 'type' => 'Hochregalstapler', 'status' => 'nicht erreichbar', 'zeitstempel' => now(), 'ladestand' => '67%', 'akkuzustand' => '87%', 'akt_ta' => null, 'meldung' => 'Nicht Erreichbar','x'=>'319','y'=>'371'],
        ['fahrzeug_id' => 'HS04', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '80%', 'akkuzustand' => '85%', 'akt_ta' => 'TA2', 'meldung' => 'Keine','x'=>'229','y'=>'95'],
        ['fahrzeug_id' => 'HS05', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '50%', 'akkuzustand' => '59%', 'akt_ta' => 'TA4', 'meldung' => 'Keine','x'=>'687','y'=>'155'],
        ['fahrzeug_id' => 'HS06', 'type' => 'Hochregalstapler', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '21%', 'akkuzustand' => '98%', 'akt_ta' => 'TA5', 'meldung' => 'Keine','x'=>'364','y'=>'371'],
        ['fahrzeug_id' => 'HS07', 'type' => 'Hochregalstapler', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '77%', 'akkuzustand' => '97%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'274','y'=>'95'],
        ['fahrzeug_id' => 'GH01', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '31%', 'akkuzustand' => '85%', 'akt_ta' => 'TA6', 'meldung' => 'Keine','x'=>'605','y'=>'155'],
        ['fahrzeug_id' => 'GH02', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '64%', 'akkuzustand' => '99%', 'akt_ta' => 'TA7', 'meldung' => 'Keine','x' => '1180', 'y' => '495'],
        ['fahrzeug_id' => 'GH03', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '76%', 'akkuzustand' => '87%', 'akt_ta' => 'TA8', 'meldung' => 'Keine','x'=>'1057','y'=>'305'],
        ['fahrzeug_id' => 'GH04', 'type' => 'Gabelhubwagen', 'status' => 'aktiv', 'zeitstempel' => now(), 'ladestand' => '17%', 'akkuzustand' => '99%', 'akt_ta' => 'TA3', 'meldung' => 'Keine','x'=>'725','y'=>'261'],
        ['fahrzeug_id' => 'GH05', 'type' => 'Gabelhubwagen', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '67%', 'akkuzustand' => '99%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'94','y'=>'95'],
        ['fahrzeug_id' => 'GH06', 'type' => 'Gabelhubwagen', 'status' => 'lädt', 'zeitstempel' => now(), 'ladestand' => '43%', 'akkuzustand' => '87%', 'akt_ta' => null, 'meldung' => 'Keine','x'=>'135','y'=>'95'],
    ];
    
    Fahrzeuge::insert($fahrzeuge);

    // === Aufträge einfügen ===
    $auftraege = [
        ['transportauftrag_id' => 'TA1', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-1', 'zielort_id' => 'HR-6-5', 'fahrzeug_id' => 'HS02'],
        ['transportauftrag_id' => 'TA2', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-2', 'zielort_id' => 'HR-1-4', 'fahrzeug_id' => 'HS04'],
        ['transportauftrag_id' => 'TA3', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-1-1', 'zielort_id' => 'BP-6', 'fahrzeug_id' => 'GH04'],
        ['transportauftrag_id' => 'TA4', 'status' => 'wird ausgeführt', 'startort_id' => 'HR-1-3', 'zielort_id' => 'BP-8', 'fahrzeug_id' => 'HS05'],
        ['transportauftrag_id' => 'TA5', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-8', 'zielort_id' => 'HR-7-4', 'fahrzeug_id' => 'HS06'],
        ['transportauftrag_id' => 'TA6', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-2-4', 'zielort_id' => 'BP-1', 'fahrzeug_id' => 'GH01'],
        ['transportauftrag_id' => 'TA7', 'status' => 'wird ausgeführt', 'startort_id' => 'BP-4', 'zielort_id' => 'WA-4-2', 'fahrzeug_id' => 'GH02'],
        ['transportauftrag_id' => 'TA8', 'status' => 'wird ausgeführt', 'startort_id' => 'WE-4-3', 'zielort_id' => 'WA-3-1', 'fahrzeug_id' => 'GH03'],
        ['transportauftrag_id' => 'TA9', 'status' => 'wartet', 'startort_id' => 'BP-3', 'zielort' => 'WA-1-1', 'fahrzeug_id' => 'GH04'],
        ['transportauftrag_id' => 'TA10', 'status' => 'wartet', 'startort_id' => 'HR-7-8', 'zielort_id' => 'BP-2', 'fahrzeug_id' => 'HS02'],
        ['transportauftrag_id' => 'TA11', 'status' => 'wartet', 'startort_id' => 'BP-6', 'zielort_id' => 'HR-5-3', 'fahrzeug_id' => 'HS03'],
        ['transportauftrag_id' => 'TA12', 'status' => 'wartet', 'startort_id' => 'BP-5', 'zielort_id' => 'WA-2-4', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA13', 'status' => 'wartet', 'startort_id' => 'BP-7', 'zielort_id' => 'WA-1-4', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA14', 'status' => 'wartet', 'startort_id' => 'BP-2', 'zielort_id' => 'HR-7-3', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA15', 'status' => 'wartet', 'startort_id' => 'WE-3-3', 'zielort_id' => 'WA-1-2', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA16', 'status' => 'wartet', 'startort_id' => 'WE-2-2', 'zielort_id' => 'BP-1', 'fahrzeug_id' => 'GHX'],
        ['transportauftrag_id' => 'TA17', 'status' => 'wartet', 'startort_id' => 'BP-5', 'zielort_id' => 'HR-7-5', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA18', 'status' => 'wartet', 'startort_id' => 'HR-6-5', 'zielort_id' => 'BP-5', 'fahrzeug_id' => 'HSX'],
        ['transportauftrag_id' => 'TA19', 'status' => 'wartet', 'startort_id' => 'HR-2-5', 'zielort_id' => 'BP-6', 'fahrzeug_id' => 'HSX'],
    ];
    foreach ($auftraege as &$a) {
        $a['created_at'] = $a['updated_at'] = now();
    }
    Auftraege::insert($auftraege);

    $routen = [
        [
            'fahrzeug_id' => 'HS04',
            'startort_id' => 'WB-5',
            'zielort_id' => 'HR-5-7',
            'routenpunkte' => ['WB-5', 'HR-7-5', 'GA-7','GA-6','HR-6-7','HR-5-7']
        ],
        [
            'fahrzeug_id' => 'GH01',
            'startort_id' => 'BP-7',
            'zielort_id' => 'WE-4-3',
            'routenpunkte' => ['BP-7-U', 'BP-8-U','BP-8-U-GA-R', 'GA-R-MITTE-U', 'WA-4','WE-4-3']
        ],
        [
            'fahrzeug_id' => 'HS02',
            'startort_id' => 'HR-4-5',
            'zielort_id' => 'BP-3',
            'routenpunkte' => ['HR-4-5', 'GA-4', 'BP-3-GA-L', 'BP-3-L']
        ],
        [
            'fahrzeug_id' => 'GH04',
            'startort_id' => 'BP-6',
            'zielort_id' => 'WA-4-4',
            'routenpunkte' => ['BP-6-R', 'BP-6-GA-R','GA-R-MITTE-U', 'WA-4','WA-4-4']
        ],
        [
            'fahrzeug_id' => 'HS05',
            'startort_id' => 'BP-8',
            'zielort_id' => 'HR-7-8',
            'routenpunkte' => ['BP-8-U', 'BP-8-U-GA-U', 'GA-L-UNTEN','BP-7-GA-L','WBG-8','HR-7-8']
        ],
        [
            'fahrzeug_id' => 'HS06',
            'startort_id' => 'BP-7',
            'zielort_id' => 'HR-5-6',
            'routenpunkte' => ['BP-7-L', 'BP-7-GA-L', 'GA-5', 'HR-5-2','HR-6-2', 'HR-6-6', 'HR-5-6']
        ],
        [
            'fahrzeug_id' => 'GH02',
            'startort_id' => 'WE-4-2',
            'zielort_id' => 'BP-2',
            'routenpunkte' => ['WE-4-2', 'WE-4', 'GA-R-MITTE-O',  'BP-2-GA-R', 'BP-2-R']
        ],
        [
            'fahrzeug_id' => 'GH03',
            'startort_id' => 'WA-3-1',
            'zielort_id' => 'BP-8',
            'routenpunkte' => ['WA-3-1', 'WE-3', 'GA-R-MITTE-O','BP-8-GA-R','BP-8-R']
        ],
    ];
        foreach ($routen as $route) {
        DB::transaction(function () use ($route) {
            $r = Routen::create([
                'fahrzeug_id' => $route['fahrzeug_id'],
                'startort_id' => $route['startort_id'],
                'zielort_id' => $route['zielort_id'],
            ]);

            foreach ($route['routenpunkte'] as $index => $rp) {
                $punkt = Routenpunkte::where('routenpunkte_id', $rp)->first();
                if ($punkt) {
                    $r->routenpunkte()->attach($punkt->routenpunkte_id, ['reihenfolge' => $index]);
                }
            }
        });
    }
    $meldungen = [
        ['fahrzeug_id' => 'GH01', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(1)],
        ['fahrzeug_id' => 'GH02', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'GH03', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'GH04', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(4)],
        ['fahrzeug_id' => 'GH05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(5)],
        ['fahrzeug_id' => 'GH06', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(6)],
        ['fahrzeug_id' => 'HS01', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'HS02', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(8)],
        ['fahrzeug_id' => 'HS03', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(9)],
        ['fahrzeug_id' => 'HS04', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(10)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(1)],
        ['fahrzeug_id' => 'HS06', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'HS07', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'GH01', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(4)],
        ['fahrzeug_id' => 'GH02', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(5)],
        ['fahrzeug_id' => 'GH03', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(6)],
        ['fahrzeug_id' => 'GH04', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'GH05', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(8)],
        ['fahrzeug_id' => 'GH06', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(9)],
        ['fahrzeug_id' => 'HS01', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(10)],
        ['fahrzeug_id' => 'HS02', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(1)],
        ['fahrzeug_id' => 'HS03', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'HS04', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(4)],
        ['fahrzeug_id' => 'HS06', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(5)],
        ['fahrzeug_id' => 'HS07', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(6)],
        ['fahrzeug_id' => 'GH01', 'typ' => 'route_blockiert', 'beschreibung' => 'Route Blockiert',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'GH02', 'typ' => 'nicht_erreichbar', 'beschreibung' => 'Nicht Erreichbar',  'gemeldet_am' => Carbon::now()->subDays(8)],
        ['fahrzeug_id' => 'GH03', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(9)],
        ['fahrzeug_id' => 'GH04', 'typ' => 'technische_wartung', 'beschreibung' => 'Technische Wartung',  'gemeldet_am' => Carbon::now()->subDays(10)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(3)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(2)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(7)],
        ['fahrzeug_id' => 'HS05', 'typ' => 'batterie_niedrig', 'beschreibung' => 'Batterie Niedrig',  'gemeldet_am' => Carbon::now()->subDays(13)],
    ];

    foreach ($meldungen as &$m) {
        $m['created_at'] = now();
        $m['updated_at'] = now();
    }

    Meldung::insert($meldungen);
    return redirect()->back()->with('success', 'Daten aktualisiert');
}

}