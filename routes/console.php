<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the daily digest to run every day at 08:00 AM
Schedule::command('system:daily-digest')->dailyAt('08:00');

// Schedule the daily audit for overdue debts to run every day at 08:00 AM
Schedule::command('app:check-overdue-debts')->dailyAt('08:00');

