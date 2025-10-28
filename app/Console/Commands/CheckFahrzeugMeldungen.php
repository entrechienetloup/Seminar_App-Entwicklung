<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fahrzeuge;
use App\Models\Meldung;
use Carbon\Carbon;

class CheckFahrzeugMeldungen extends Command
{
    protected $signature = 'check:fahrzeug-meldungen'; // Command Name -> aufrufbar mit: php artisan check:fahrzeug-meldungen 
    protected $description = 'check ob neue Meldungen und in der Datenbank speichern';
    public function handle(): int
    {
        $fahrzeuge = Fahrzeuge::all();

        foreach ($fahrzeuge as $fahrzeug) {
            
            $meldungText = trim($fahrzeug->meldung); // Holt die aktuelle Meldung aus dem Fahrzeug

            if (strtolower($meldungText) === 'keine') { // keine Meldung? -> überspringen
                continue;
            }

            $typ = strtolower(str_replace(' ', '_', $meldungText));

            // Prüft, ob in der letzten Stunde bereits eine identische Meldung existiert
            $alreadyExists = Meldung::where('fahrzeug_id', $fahrzeug->fahrzeug_id)
                ->where('typ', $typ)
                ->where('gemeldet_am', '>=', Carbon::now()->subHour())
                ->exists();

            // Wenn keine identische Meldung in der letzten Stunde existiert, wird eine neue Meldung erstellt
            if (!$alreadyExists) {
                Meldung::create([
                    'fahrzeug_id' => $fahrzeug->fahrzeug_id,
                    'typ' => $typ,
                    'beschreibung' => $meldungText,
                    'gemeldet_am' => now(),
                    //'status' => 'offen',
                ]);
            }
        }

        return Command::SUCCESS;
    }
}
