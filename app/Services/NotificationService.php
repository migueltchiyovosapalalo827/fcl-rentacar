<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function notify(User $user, string $title, string $message, string $type = 'sistema'): Notification
    {
        return $user->notifications()->create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
            'created_at' => now(),
        ]);
    }
}


