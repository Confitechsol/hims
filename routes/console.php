<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daywise bed charges calculation daily at 10:05 AM
Schedule::command('ipd:calculate-bed-charges')
    ->dailyAt('10:05')
    ->timezone('Asia/Kolkata') // Adjust to your server timezone
    ->withoutOverlapping()
    ->runInBackground()
    // Removed onOneServer() to avoid cache dependency issues
    ->appendOutputTo(storage_path('logs/bed-charges-scheduler.log'))
    ->emailOutputOnFailure(env('ADMIN_EMAIL', null)); // Optional: email on failure
