<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\CheckFahrzeugMeldungen;

Schedule::command('fahrzeuge:akku-reduzieren')->everyTenSeconds();
Schedule::command(CheckFahrzeugMeldungen::class)->everyTenSeconds();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
