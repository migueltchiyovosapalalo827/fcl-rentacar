<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Deposit;
use App\Models\Driver;
use App\Models\Location;
use App\Models\MaintenanceReport;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Reservation;
use App\Policies\CarPolicy;
use App\Policies\DepositPolicy;
use App\Policies\DriverPolicy;
use App\Policies\LocationPolicy;
use App\Policies\MaintenanceReportPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ReservationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Car::class => CarPolicy::class,
        Reservation::class => ReservationPolicy::class,
        Driver::class => DriverPolicy::class,
        Location::class => LocationPolicy::class,
        Payment::class => PaymentPolicy::class,
        Notification::class => NotificationPolicy::class,
        Deposit::class => DepositPolicy::class,
        MaintenanceReport::class => MaintenanceReportPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
