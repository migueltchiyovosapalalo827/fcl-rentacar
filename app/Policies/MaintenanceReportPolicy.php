<?php

namespace App\Policies;

use App\Models\MaintenanceReport;
use App\Models\User;

class MaintenanceReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_maintenance');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceReport $maintenanceReport): bool
    {
        return $user->can('view_maintenance');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_maintenance');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenanceReport $maintenanceReport): bool
    {
        // Técnicos podem editar seus próprios relatórios
        if ($user->hasRole('technician') && $maintenanceReport->technician_id === $user->id) {
            return true;
        }
        
        return $user->can('edit_maintenance');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceReport $maintenanceReport): bool
    {
        return $user->can('delete_maintenance');
    }
}
