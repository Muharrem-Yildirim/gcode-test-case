<?php

use App\Services\TCMBImporter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('app:import-today', function () {
    $this->info("Importing TCMB data for today");
})->purpose('Import Today')->daily();
