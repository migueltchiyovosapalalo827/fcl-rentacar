<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@rentacar.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'phone' => '+244 923 456 789',
                'address' => 'Luanda, Angola',
                'role' => 'gerente',
            ]
        );
        $admin->forceFill(['role' => 'gerente'])->save();
        $admin->assignRole('admin');

        // Criar Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@rentacar.com'],
            [
                'name' => 'Gerente',
                'password' => Hash::make('password'),
                'phone' => '+244 923 456 790',
                'address' => 'Luanda, Angola',
                'role' => 'gerente',
            ]
        );
        $manager->forceFill(['role' => 'gerente'])->save();
        $manager->assignRole('manager');

        // Criar Técnico
        $technician = User::firstOrCreate(
            ['email' => 'tecnico@rentacar.com'],
            [
                'name' => 'Técnico de Manutenção',
                'password' => Hash::make('password'),
                'phone' => '+244 923 456 791',
                'address' => 'Luanda, Angola',
                'role' => 'tecnico',
            ]
        );
        $technician->forceFill(['role' => 'tecnico'])->save();
        $technician->assignRole('technician');

        // Criar alguns clientes
        $clientRole = Role::where('name', 'client')->first();
        User::factory(10)->create(['role' => 'cliente'])->each(function ($user) use ($clientRole) {
            if ($clientRole) {
                $user->assignRole($clientRole);
            }
        });

        // Criar alguns motoristas
        $driverRole = Role::where('name', 'driver')->first();
        User::factory(5)->create(['role' => 'motorista'])->each(function ($user) use ($driverRole) {
            if ($driverRole) {
                $user->assignRole($driverRole);
            }
        });
    }
}

