<?php

namespace App\Observers;

use App\Mail\ReservationNotificationMail;
use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationObserver
{
    public function updated(Reservation $reservation): void
    {
        if ($reservation->wasChanged('status')) {
            $reservation->loadMissing(['car', 'client', 'pickupLocation', 'dropoffLocation']);
            $this->createStatusNotification($reservation, $reservation->status);
        }
    }

    protected function createStatusNotification(Reservation $reservation, string $newStatus): void
    {
        $messages = [
            'confirmada' => [
                'title' => 'Reserva Confirmada',
                'message' => "Sua reserva #{$reservation->id} foi confirmada! O veículo {$reservation->car?->brand} {$reservation->car?->model} está reservado para você.",
            ],
            'ativa' => [
                'title' => 'Reserva Ativada',
                'message' => "Sua reserva #{$reservation->id} está agora ativa. Você pode retirar o veículo no local de recolha.",
            ],
            'concluida' => [
                'title' => 'Reserva Concluída',
                'message' => "Sua reserva #{$reservation->id} foi concluída com sucesso. Obrigado por escolher nossos serviços! Avalie sua experiência na área do cliente.",
            ],
            'cancelada' => [
                'title' => 'Reserva Cancelada',
                'message' => "Sua reserva #{$reservation->id} foi cancelada.",
            ],
        ];

        if (! isset($messages[$newStatus])) {
            return;
        }

        $client = $reservation->client;
        if (! $client) {
            return;
        }

        Notification::create([
            'user_id' => $reservation->client_id,
            'title' => $messages[$newStatus]['title'],
            'message' => $messages[$newStatus]['message'],
            'type' => 'reserva',
            'is_read' => false,
            'created_at' => now(),
        ]);

        try {
            Mail::to($client->email)->send(
                new ReservationNotificationMail(
                    $reservation,
                    'reserva',
                    $messages[$newStatus]['title'],
                    $messages[$newStatus]['message']
                )
            );
        } catch (\Exception $e) {
            Log::error('Failed to send reservation email: '.$e->getMessage());
        }
    }
}
