<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_reservations');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        // Clientes só podem ver suas próprias reservas
        if ($user->hasRole('client')) {
            return $reservation->client_id === $user->id;
        }
        
        return $user->can('view_reservations');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_reservations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        // Clientes só podem editar suas próprias reservas pendentes
        if ($user->hasRole('client')) {
            return $reservation->client_id === $user->id && $reservation->status === 'pendente';
        }
        
        return $user->can('edit_reservations');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        // Clientes só podem cancelar suas próprias reservas pendentes
        if ($user->hasRole('client')) {
            return $reservation->client_id === $user->id && in_array($reservation->status, ['pendente', 'confirmada']);
        }
        
        return $user->can('delete_reservations');
    }

    /**
     * Determine whether the user can approve the reservation.
     */
    public function approve(User $user, Reservation $reservation): bool
    {
        return $user->can('approve_reservations');
    }

    /**
     * Determine whether the user can cancel the reservation.
     */
    public function cancel(User $user, Reservation $reservation): bool
    {
        return $user->can('cancel_reservations') || 
               ($user->hasRole('client') && $reservation->client_id === $user->id);
    }
}
