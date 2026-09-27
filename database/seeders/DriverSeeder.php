<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driverRole = Role::where('name', 'driver')->first();
        
        // Criar motoristas para usuários com role driver que ainda não têm perfil
        $drivers = User::role('driver')->get();
        
        foreach ($drivers as $user) {
            if (!$user->driverProfile) {
                Driver::factory()->create([
                    'user_id' => $user->id,
                    'availability' => 'livre',
                ]);
            }
        }
        
        // Criar alguns motoristas adicionais livres
        Driver::factory(3)->livre()->create()->each(function (Driver $driver) use ($driverRole) {
            $driver->user?->forceFill(['role' => 'motorista'])->save();
            if ($driverRole && $driver->user) {
                $driver->user->assignRole($driverRole);
            }
        });
    }
}

