<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendar comando de lembretes de reserva (executa diariamente às 9h)
Schedule::command('reservations:send-reminders')
    ->dailyAt('09:00')
    ->timezone('Africa/Luanda');
