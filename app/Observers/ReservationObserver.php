<?php

namespace App\Observers;

use App\Mail\ReservationNotificationMail;
use App\Models\Notification;
use App\Models\Reservation;
use App\Notifications\ReservationStatusNotification;
use Illuminate\Support\Facades\Mail;

class ReservationObserver
{
    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        // Verificar se o status mudou
        if ($reservation->wasChanged('status')) {
            $oldStatus = $reservation->getOriginal('status');
            $newStatus = $reservation->status;

            // Carregar relacionamentos necessários
            $reservation->load(['car', 'pickupLocation']);

            // Criar notificação baseada no novo status
            $this->createStatusNotification($reservation, $oldStatus, $newStatus);
        }
    }

    /**
     * Criar notificação baseada na mudança de status
     */
    protected function createStatusNotification(Reservation $reservation, string $oldStatus, string $newStatus): void
    {
        $messages = [
            'confirmada' => [
                'title' => 'Reserva Confirmada',
                'message' => "Sua reserva #{$reservation->id} foi confirmada! O veículo {$reservation->car->brand} {$reservation->car->model} está reservado para você.",
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

        if (isset($messages[$newStatus])) {
            $client = $reservation->client;
            
            // Criar notificação no banco de dados
            Notification::create([
                'user_id' => $reservation->client_id,
                'title' => $messages[$newStatus]['title'],
                'message' => $messages[$newStatus]['message'],
                'type' => 'reserva',
                'is_read' => false,
                'created_at' => now(),
            ]);

            // Enviar email
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
                // Log error but don't fail
                \Log::error('Failed to send reservation email: ' . $e->getMessage());
            }

            // Enviar notificação push (database)
            try {
                $client->notify(
                    new ReservationStatusNotification(
                        $reservation,
                        'reserva',
                        $messages[$newStatus]['title'],
                        $messages[$newStatus]['message']
                    )
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation notification: ' . $e->getMessage());
            }
        }
    }
}
