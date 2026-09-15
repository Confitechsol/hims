<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hostinger-friendly safety net: drain queues every minute via cron schedule:run.
// Primary path on Hostinger is QUEUE_AUTO_DRAIN after each web request (no long-lived worker).
// Hostinger cron (required for this schedule):
//   * * * * * cd /home/USER/domains/YOURDOMAIN/public_html && php artisan schedule:run >> /dev/null 2>&1
Schedule::command('queue:work --queue=audit,bed-charges,default --stop-when-empty --max-time=50 --tries=3')
    ->everyMinute()
    ->withoutOverlapping(2)
    ->appendOutputTo(storage_path('logs/queue-scheduler.log'));

// Schedule daywise bed charges calculation daily at 10:05 AM
// DISABLED: Bed charges are now calculated dynamically from PatientBedHistory when generating bills
// Schedule::command('ipd:calculate-bed-charges')
//     ->dailyAt('10:05')
//     ->timezone('Asia/Kolkata') // Adjust to your server timezone
//     ->withoutOverlapping()
//     ->runInBackground()
//     // Removed onOneServer() to avoid cache dependency issues
//     ->appendOutputTo(storage_path('logs/bed-charges-scheduler.log'))
//     ->emailOutputOnFailure(env('ADMIN_EMAIL', null)); // Optional: email on failure
