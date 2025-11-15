<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $reservations = Reservation::where('client_id', $user->id)
            ->with(['car', 'pickupLocation', 'dropoffLocation', 'driver'])
            ->latest()
            ->limit(5)
            ->get();
        
        $stats = [
            'total_reservations' => Reservation::where('client_id', $user->id)->count(),
            'active_reservations' => Reservation::where('client_id', $user->id)
                ->whereIn('status', ['pendente', 'confirmada', 'ativa'])
                ->count(),
            'completed_reservations' => Reservation::where('client_id', $user->id)
                ->where('status', 'concluida')
                ->count(),
            'unread_notifications' => $user->notifications()->where('is_read', false)->count(),
        ];
        
        return view('client.dashboard', compact('reservations', 'stats'));
    }
}
