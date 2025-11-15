<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest('created_at')
            ->paginate(15);
        
        $unreadCount = $user->notifications()->where('is_read', false)->count();
        
        return view('client.notifications.index', compact('notifications', 'unreadCount'));
    }
    
    public function markAsRead(Notification $notification)
    {
        // Verificar se a notificação pertence ao usuário autenticado
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para esta ação.');
        }
        
        $notification->update(['is_read' => true]);
        
        return redirect()->back()
            ->with('success', 'Notificação marcada como lida.');
    }
    
    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return redirect()->back()
            ->with('success', 'Todas as notificações foram marcadas como lidas.');
    }
    
    public function destroy(Notification $notification)
    {
        // Verificar se a notificação pertence ao usuário autenticado
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para esta ação.');
        }
        
        $notification->delete();
        
        return redirect()->back()
            ->with('success', 'Notificação removida.');
    }
}
