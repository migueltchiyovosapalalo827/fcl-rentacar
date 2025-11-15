<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_payments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
        // Clientes só podem ver pagamentos de suas próprias reservas
        if ($user->hasRole('client')) {
            return $payment->reservation->client_id === $user->id;
        }
        
        return $user->can('view_payments');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_payments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->can('edit_payments');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $user->can('delete_payments');
    }

    /**
     * Determine whether the user can process the payment.
     */
    public function process(User $user, Payment $payment): bool
    {
        return $user->can('process_payments');
    }

    /**
     * Determine whether the user can refund the payment.
     */
    public function refund(User $user, Payment $payment): bool
    {
        return $user->can('refund_payments');
    }
}
