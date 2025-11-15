<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $reservations = Reservation::where('client_id', $user->id)
            ->with(['car', 'pickupLocation', 'dropoffLocation', 'driver', 'payments', 'deposit'])
            ->latest()
            ->paginate(10);
        
        return view('client.reservations.index', compact('reservations'));
    }
    
    public function show(Reservation $reservation)
    {
        // Verificar se a reserva pertence ao usuário autenticado
        if ($reservation->client_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para ver esta reserva.');
        }
        
        $reservation->load(['car', 'pickupLocation', 'dropoffLocation', 'driver', 'payments', 'deposit', 'review.reward']);
        
        return view('client.reservations.show', compact('reservation'));
    }
    
    public function cancel(Reservation $reservation)
    {
        // Verificar se a reserva pertence ao usuário autenticado
        if ($reservation->client_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para cancelar esta reserva.');
        }
        
        // Verificar se pode ser cancelada
        if (!in_array($reservation->status, ['pendente', 'confirmada'])) {
            return redirect()->back()
                ->with('error', 'Esta reserva não pode ser cancelada.');
        }
        
        $reservation->update(['status' => 'cancelada']);
        
        return redirect()->route('client.reservations.show', $reservation)
            ->with('success', 'Reserva cancelada com sucesso.');
    }
}
