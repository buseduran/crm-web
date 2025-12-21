<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// AI raporunu günde 1 kez oluştur (her gün saat 08:00'de)
Schedule::command('ai:generate-report')
    ->dailyAt('08:00')
    ->timezone('Europe/Istanbul')
    ->withoutOverlapping()
    ->runInBackground();
