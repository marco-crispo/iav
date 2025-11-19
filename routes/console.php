<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Aggiungi lo scheduling del tuo comando:
Schedule::command('app:sync-online-users')->everyFiveMinutes();
// crontab -e
// * * * * * cd /path/to/laravel && php artisan schedule:run >> /dev/null 2>&1
