<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ReservationNotificationMail;
use App\Models\Car;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Reservation;
use App\Notifications\ReservationStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function create(Request $request)
    {
        // Requer autenticação para criar reserva
        if (!Auth::check()) {
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
        // Requer autenticação para criar reserva
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Por favor, faça login para criar uma reserva.');
        }

        // Preparar dados do formulário - garantir que with_driver seja boolean
        $request->merge([
            'with_driver' => $request->has('with_driver') && $request->with_driver == '1'
        ]);

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'pickup_location_id' => 'required|exists:locations,id',
            'dropoff_location_id' => 'required|exists:locations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'purpose' => 'required|in:negocios,casamento,passeio,trabalho,outros',
            'with_driver' => 'sometimes|boolean',
            'driver_id' => 'nullable|exists:drivers,id|required_if:with_driver,1',
        ], [
            'car_id.required' => 'Por favor, selecione um carro.',
            'car_id.exists' => 'O carro selecionado não existe.',
            'pickup_location_id.required' => 'Por favor, selecione o local de recolha.',
            'pickup_location_id.exists' => 'O local de recolha selecionado não existe.',
            'dropoff_location_id.required' => 'Por favor, selecione o local de devolução.',
            'dropoff_location_id.exists' => 'O local de devolução selecionado não existe.',
            'start_date.required' => 'Por favor, selecione a data de início.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'end_date.required' => 'Por favor, selecione a data de fim.',
            'end_date.date' => 'A data de fim deve ser uma data válida.',
            'end_date.after' => 'A data de fim deve ser posterior à data de início.',
            'purpose.required' => 'Por favor, selecione o propósito da reserva.',
            'driver_id.required_if' => 'Por favor, selecione um motorista quando optar por ter motorista.',
        ]);

        // Validar que a data de início é no futuro
        $startDate = new \DateTime($validated['start_date']);
        $now = new \DateTime();
        if ($startDate <= $now) {
            return redirect()->back()
                ->withErrors(['start_date' => 'A data de início deve ser no futuro.'])
                ->withInput();
        }

        try {
            // Calcular valor total
            $car = Car::findOrFail($validated['car_id']);
            $startDate = new \DateTime($validated['start_date']);
            $endDate = new \DateTime($validated['end_date']);
            
            // Calcular diferença em dias (arredondar para cima)
            $diff = $startDate->diff($endDate);
            $days = max(1, (int) ceil($diff->days + ($diff->h / 24) + ($diff->i / 1440)));
            
            $totalAmount = $car->price_per_day * $days;

            if (!empty($validated['with_driver'])) {
                $totalAmount += 50 * $days; // Taxa do motorista
            }

            $reservation = Reservation::create([
                'client_id' => Auth::id(),
                'car_id' => $validated['car_id'],
                'driver_id' => $validated['driver_id'] ?? null,
                'pickup_location_id' => $validated['pickup_location_id'],
                'dropoff_location_id' => $validated['dropoff_location_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'purpose' => $validated['purpose'],
                'with_driver' => !empty($validated['with_driver']),
                'status' => 'pendente',
                'total_amount' => $totalAmount,
            ]);

            $client = Auth::user();
            $title = 'Reserva Criada';
            $message = "Sua reserva #{$reservation->id} foi criada com sucesso e está pendente de confirmação.";

            // Criar notificação no banco de dados
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
                \Log::error('Failed to create notification: ' . $e->getMessage());
            }

            // Enviar email
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
                \Log::error('Failed to send reservation creation email: ' . $e->getMessage());
            }

            // Enviar notificação push
            try {
                $client->notify(
                    new ReservationStatusNotification(
                        $reservation,
                        'reserva',
                        $title,
                        $message
                    )
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation creation notification: ' . $e->getMessage());
            }

            return redirect()->route('public.reservations.success', $reservation->id)
                ->with('success', 'Reserva criada com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Failed to create reservation: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao criar a reserva. Por favor, tente novamente.')
                ->withInput();
        }
    }

    public function success(Reservation $reservation)
    {
        // Verificar se a reserva pertence ao usuário autenticado
        if (Auth::check() && $reservation->client_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para ver esta reserva.');
        }
        
        return view('public.reservations.success', compact('reservation'));
    }
}
