<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos podem ver locais (incluindo clientes não autenticados)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Location $location): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_locations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Location $location): bool
    {
        return $user->can('edit_locations');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Location $location): bool
    {
        return $user->can('delete_locations');
    }
}
