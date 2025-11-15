<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar Permissões
        $permissions = [
            // Carros
            'view_cars',
            'create_cars',
            'edit_cars',
            'delete_cars',
            
            // Reservas
            'view_reservations',
            'create_reservations',
            'edit_reservations',
            'delete_reservations',
            'approve_reservations',
            'cancel_reservations',
            
            // Motoristas
            'view_drivers',
            'create_drivers',
            'edit_drivers',
            'delete_drivers',
            
            // Locais
            'view_locations',
            'create_locations',
            'edit_locations',
            'delete_locations',
            
            // Pagamentos
            'view_payments',
            'create_payments',
            'edit_payments',
            'delete_payments',
            'process_payments',
            'refund_payments',
            
            // Notificações
            'view_notifications',
            'create_notifications',
            'edit_notifications',
            'delete_notifications',
            
            // Relatórios
            'view_reports',
            'create_reports',
            'edit_reports',
            'delete_reports',
            
            // Manutenção
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            'delete_maintenance',
            
            // Depósitos
            'view_deposits',
            'create_deposits',
            'edit_deposits',
            'refund_deposits',
            
            // Usuários
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $clientRole = Role::firstOrCreate(['name' => 'client']);
        $driverRole = Role::firstOrCreate(['name' => 'driver']);
        $technicianRole = Role::firstOrCreate(['name' => 'technician']);

        // Atribuir todas as permissões ao admin
        $adminRole->givePermissionTo(Permission::all());

        // Permissões do Manager
        $managerRole->givePermissionTo([
            'view_cars', 'create_cars', 'edit_cars',
            'view_reservations', 'create_reservations', 'edit_reservations', 'approve_reservations', 'cancel_reservations',
            'view_drivers', 'create_drivers', 'edit_drivers',
            'view_locations', 'create_locations', 'edit_locations',
            'view_payments', 'create_payments', 'edit_payments', 'process_payments',
            'view_notifications', 'create_notifications', 'edit_notifications',
            'view_reports', 'create_reports', 'edit_reports',
            'view_maintenance', 'create_maintenance', 'edit_maintenance',
            'view_deposits', 'create_deposits', 'edit_deposits', 'refund_deposits',
            'view_users', 'create_users', 'edit_users',
        ]);

        // Permissões do Client
        $clientRole->givePermissionTo([
            'view_cars',
            'view_reservations', 'create_reservations', 'edit_reservations',
            'view_payments', 'create_payments',
            'view_notifications',
        ]);

        // Permissões do Driver
        $driverRole->givePermissionTo([
            'view_reservations',
            'view_notifications',
        ]);

        // Permissões do Technician
        $technicianRole->givePermissionTo([
            'view_cars',
            'view_reservations',
            'view_maintenance', 'create_maintenance', 'edit_maintenance',
            'view_notifications',
        ]);
    }
}

