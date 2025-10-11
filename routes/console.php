<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('generate:daily-time-slots', function () {
    Artisan::call('generate:daily-time-slots');
    $this->info('Daily time slots command executed successfully!');
})->describe('Generate daily time slots for mechanics');

// Tambahkan Schedule Command
app()->booted(function () {
    $schedule = app(Schedule::class);

    $schedule->command('generate:daily-time-slots')->dailyAt('00:00');
});
