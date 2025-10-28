<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Models\Fahrzeuge;
use App\Models\Auftraege;
use App\Models\Meldung;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {

        if (auth()->user()->role !== 'leitung') {
            return redirect('/dashboard');
        }

        // Fetch models
        $fahrzeuge = Fahrzeuge::all();
        $auftraege = Auftraege::all();
        //$meldungen = Meldung::all();
        // Fahrzeuge stats
        $activeFahrzeuge = $fahrzeuge->where('status', 'aktiv')->count();
        $totalFahrzeuge = $fahrzeuge->count();

        // avg. Akkuzustand    string --> float
        $avgAkku = round(
        $fahrzeuge->pluck('akkuzustand')
        ->map(fn($x) => is_string($x) ? floatval(rtrim($x, '%')) : (is_numeric($x) ? floatval($x) : null))
        ->filter() // Filter out nulls
        ->avg(),
            1
        );

    $minAkkuFahrzeug = $fahrzeuge
    ->filter(fn($fzg) => isset($fzg->akkuzustand)  && is_numeric(rtrim($fzg->akkuzustand, '%')))
    ->sortBy(fn($fzg) => floatval(rtrim($fzg->akkuzustand, '%'))) // asc sortieren
    ->first();

    $minAkku = $minAkkuFahrzeug 
    ? floatval(rtrim($minAkkuFahrzeug->akkuzustand, '%')) 
    : null;

        // Utilization
    //$fahrzeugeInUse = $fahrzeuge->whereNotNull('transportauftrag_id')->count();
    //$utilization = $totalFahrzeuge ? round($fahrzeugeInUse / $totalFahrzeuge * 100, 1) : 0;

        // Fahrzeugtyp
    $fahrzeugTypen = \App\Models\Fahrzeuge::select('type', \DB::raw('count(*) as total'))
    ->groupBy('type')
    ->orderByDesc('total')
    ->get();


        // Auftraege stats
        $activeAuftraege = $auftraege->where('status', 'wird ausgeführt')->count();
        $queuedAuftraege = $auftraege->where('status', 'wartet')->count();




        // Aufträge,  heute 
        $todayAuftraege = $auftraege->where(fn($a) =>
            optional($a->created_at)->isToday()
        )->count();

        // Aufträge,   letzte 7 Tage 
        $createdLast7Days = $auftraege->where(fn($a) =>
            optional($a->created_at)->greaterThanOrEqualTo(now()->subDays(7))
        )->count();

        // avg pro Tag
        $avgCreatedPerDay = $createdLast7Days ? round($createdLast7Days / 7, 1) : 0;

        // Anzahl heute + Gesamtzahl 
        $now = now();
        $meldungenToday = Meldung::whereDate('gemeldet_am', today())->count();
        $totalMeldungen = Meldung::count();

        //$secondsPassedToday = now()->diffInSeconds(now()->copy()->startOfDay());

        // Stoerungsrate
        $firstMeldung = Meldung::orderBy('gemeldet_am')->first();
        $lastMeldung = Meldung::orderByDesc('gemeldet_am')->first();

        if ($totalMeldungen >= 2 && $firstMeldung && $lastMeldung) {
            $hoursSpan = $firstMeldung->gemeldet_am->diffInHours($lastMeldung->gemeldet_am);
            $stoerungsrate = $hoursSpan > 0 ? round($hoursSpan / $totalMeldungen, 1) : null;
        } else {
            $stoerungsrate = null;
        }

        // Durchschnittliche Meldungen pro Tag in der letzten Woche
        $weekStart = now()->startOfWeek(); // Montag diese Woche
        $lastWeekStart = now()->subWeek()->startOfWeek(); // Montag letzte Woche
        $lastWeekEnd = $weekStart; 

        $thisWeekMeldungen = Meldung::where('gemeldet_am', '>=', $weekStart)->count();
        $lastWeekMeldungen = Meldung::whereBetween('gemeldet_am', [$lastWeekStart, $lastWeekEnd])->count();

        $meldungChange = $lastWeekMeldungen ? round((($thisWeekMeldungen - $lastWeekMeldungen) / $lastWeekMeldungen) * 100, 1) : null;

        $avgMeldungenPerDay = $lastWeekMeldungen ? round($lastWeekMeldungen / 7, 1) : 0;

        // Meldungen für die Tabelle (Seitenweise)
        $meldungen = Meldung::latest('gemeldet_am')->paginate(5);

        // Fahrzeuge mit den meisten Meldungen in den letzten 30 Tagen
        $fahrzeugeMitMeldungen = Fahrzeuge::withCount(['meldungen as meldungen_count' => function ($query) {
        $query->where('gemeldet_am', '>=', now()->subDays(30));
        }])
            ->with(['meldungen' => function ($query) {
            $query->where('gemeldet_am', '>=', now()->subDays(30));
        }])
        ->get()
        ->map(function ($fahrzeug) {
            $typCounts = $fahrzeug->meldungen->groupBy('typ')->map->count();
            return (object)[
            'fahrzeug_id' => $fahrzeug->fahrzeug_id,
            'meldungen_count' => $fahrzeug->meldungen_count,
            'meldungstypen' => $typCounts,
            ];
            })
        ->sortByDesc('meldungen_count')
        ->take(5);

        // Meldungstypen sammeln für Spaltenüberschriften
        $alleTypen = collect();

        foreach ($fahrzeugeMitMeldungen as $fahrzeug) {
            $alleTypen = $alleTypen->merge(collect($fahrzeug->meldungstypen)->keys());
        }

        $alleTypen = $alleTypen->unique()->values();



        
        //if (request()->ajax()) {
    //return view('partials.meldungTable', ['meldungen' => $meldungen]);
//}
        // Daten an die Blade-Ansicht übergeben
        return view('pages.stats', compact(
            'activeFahrzeuge',
            'totalFahrzeuge',
            'activeAuftraege',
            'queuedAuftraege',
            'thisWeekMeldungen',
            'lastWeekMeldungen',
            'meldungChange',
            'meldungen',
            'avgCreatedPerDay',
            'todayAuftraege',
            'meldungenToday',
            'stoerungsrate',
            'totalMeldungen',
            'avgMeldungenPerDay',
            'avgAkku',
            'minAkku',
            'minAkkuFahrzeug',
            'fahrzeugTypen',
            'fahrzeugeMitMeldungen',
            'alleTypen',
        ));
    }
}
