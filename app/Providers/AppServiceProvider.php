<?php

namespace App\Providers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Observers\PaymentObserver;
use App\Observers\ReservationObserver;
use App\Repositories\Contracts\CarRepositoryInterface;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use App\Repositories\Eloquent\CarRepository;
use App\Repositories\Eloquent\ReservationRepository;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CarRepositoryInterface::class, CarRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        
        // Registrar Observers
        Reservation::observe(ReservationObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
