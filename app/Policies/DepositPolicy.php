<?php

namespace App\Policies;

use App\Models\Deposit;
use App\Models\User;

class DepositPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_deposits');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Deposit $deposit): bool
    {
        // Clientes só podem ver depósitos de suas próprias reservas
        if ($user->hasRole('client')) {
            return $deposit->reservation->client_id === $user->id;
        }
        
        return $user->can('view_deposits');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_deposits');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Deposit $deposit): bool
    {
        return $user->can('edit_deposits');
    }

    /**
     * Determine whether the user can refund the deposit.
     */
    public function refund(User $user, Deposit $deposit): bool
    {
        return $user->can('refund_deposits');
    }
}
