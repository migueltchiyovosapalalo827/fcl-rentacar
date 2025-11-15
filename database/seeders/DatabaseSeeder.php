<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            LocationSeeder::class,
            UserSeeder::class,
            CarSeeder::class,
            DriverSeeder::class,
            ReservationSeeder::class,
            PaymentSeeder::class,
            DepositSeeder::class,
            NotificationSeeder::class,
            MaintenanceReportSeeder::class,
        ]);
    }
}
