<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
});

Schedule::command('app:generate-fines')
    ->daily();

Schedule::command('app:expire-subscriptions')
    ->daily();

Schedule::command('reservations:expire')
    ->hourly();