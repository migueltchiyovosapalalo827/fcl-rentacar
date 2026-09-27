<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ReservationNotificationMail;
use App\Models\Car;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationService $reservationService
    ) {}

    public function create(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Por favor, faça login ou registre-se para criar uma reserva.');
        }

        $cars = Car::where('status', 'disponivel')->get();
        $locations = Location::where('active', true)->get();
        $selectedCarId = $request->get('car_id');

        return view('public.reservations.create', compact('cars', 'locations', 'selectedCarId'));
    }

    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Por favor, faça login para criar uma reserva.');
        }

        $request->merge([
            'with_driver' => $request->boolean('with_driver'),
        ]);

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'pickup_location_id' => 'required|exists:locations,id',
            'dropoff_location_id' => 'required|exists:locations,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'purpose' => 'required|in:negocios,casamento,passeio,trabalho,outros',
            'with_driver' => 'sometimes|boolean',
            'driver_id' => 'nullable|exists:drivers,id',
        ], [
            'car_id.required' => 'Por favor, selecione um carro.',
            'car_id.exists' => 'O carro selecionado não existe.',
            'pickup_location_id.required' => 'Por favor, selecione o local de recolha.',
            'pickup_location_id.exists' => 'O local de recolha selecionado não existe.',
            'dropoff_location_id.required' => 'Por favor, selecione o local de devolução.',
            'dropoff_location_id.exists' => 'O local de devolução selecionado não existe.',
            'start_date.required' => 'Por favor, selecione a data de início.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'start_date.after' => 'A data de início deve ser no futuro.',
            'end_date.required' => 'Por favor, selecione a data de fim.',
            'end_date.date' => 'A data de fim deve ser uma data válida.',
            'end_date.after' => 'A data de fim deve ser posterior à data de início.',
            'purpose.required' => 'Por favor, selecione o propósito da reserva.',
        ]);

        $reservation = $this->reservationService->createReservation([
            ...$validated,
            'client_id' => Auth::id(),
        ]);

        $this->notifyReservationCreated($reservation);

        return redirect()->route('public.reservations.success', $reservation->id)
            ->with('success', 'Reserva criada com sucesso!');
    }

    public function success(Reservation $reservation)
    {
        if (Auth::check() && $reservation->client_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para ver esta reserva.');
        }

        $reservation->load(['car', 'pickupLocation', 'dropoffLocation']);

        return view('public.reservations.success', compact('reservation'));
    }

    private function notifyReservationCreated(Reservation $reservation): void
    {
        $reservation->loadMissing(['client', 'car', 'pickupLocation', 'dropoffLocation']);
        $client = $reservation->client;

        if (! $client) {
            return;
        }

        $title = 'Reserva Criada';
        $message = "Sua reserva #{$reservation->id} foi criada com sucesso e está pendente de confirmação.";

        try {
            Notification::create([
                'user_id' => $client->id,
                'title' => $title,
                'message' => $message,
                'type' => 'reserva',
                'is_read' => false,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: '.$e->getMessage());
        }

        try {
            Mail::to($client->email)->send(
                new ReservationNotificationMail(
                    $reservation,
                    'reserva',
                    $title,
                    $message
                )
            );
        } catch (\Exception $e) {
            Log::error('Failed to send reservation creation email: '.$e->getMessage());
        }
    }
}
