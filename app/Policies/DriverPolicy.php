<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_drivers');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Driver $driver): bool
    {
        // Motoristas podem ver seu próprio perfil
        if ($user->hasRole('driver') && $driver->user_id === $user->id) {
            return true;
        }
        
        return $user->can('view_drivers');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_drivers');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Driver $driver): bool
    {
        // Motoristas podem editar seu próprio perfil
        if ($user->hasRole('driver') && $driver->user_id === $user->id) {
            return true;
        }
        
        return $user->can('edit_drivers');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Driver $driver): bool
    {
        return $user->can('delete_drivers');
    }
}
