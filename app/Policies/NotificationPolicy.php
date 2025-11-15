<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_notifications');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Notification $notification): bool
    {
        // Usuários só podem ver suas próprias notificações
        return $notification->user_id === $user->id || $user->can('view_notifications');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_notifications');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Notification $notification): bool
    {
        // Usuários podem marcar suas próprias notificações como lidas
        if ($notification->user_id === $user->id) {
            return true;
        }
        
        return $user->can('edit_notifications');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Notification $notification): bool
    {
        // Usuários podem deletar suas próprias notificações
        if ($notification->user_id === $user->id) {
            return true;
        }
        
        return $user->can('delete_notifications');
    }
}
