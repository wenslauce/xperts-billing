<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Domain expiry check - runs daily at 8:00 AM
Schedule::command('domains:check-expiry')->dailyAt('08:00');

// Email piping - fetch emails every 5 minutes
Schedule::command('email-piping:fetch')->everyFiveMinutes();

// Service suspension check - runs daily at 02:00
Schedule::command('services:check-suspension')->dailyAt('02:00');

// Renewal invoice generation - runs daily at 03:00
Schedule::command('invoices:generate-renewals')->dailyAt('03:00');

// Invoice reminders - runs daily at 09:00
Schedule::command('invoices:send-reminders')->dailyAt('09:00');
