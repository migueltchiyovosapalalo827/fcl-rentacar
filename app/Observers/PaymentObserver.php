<?php

namespace App\Observers;

use App\Mail\ReservationNotificationMail;
use App\Models\Notification;
use App\Models\Payment;
use App\Notifications\ReservationStatusNotification;
use Illuminate\Support\Facades\Mail;

class PaymentObserver
{
    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // Verificar se o status mudou para "pago"
        if ($payment->wasChanged('status') && $payment->status === 'pago') {
            $this->createPaymentNotification($payment);
        }
    }

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        // Se o pagamento já foi criado como pago
        if ($payment->status === 'pago') {
            $this->createPaymentNotification($payment);
        }
    }

    /**
     * Criar notificação de pagamento processado
     */
    protected function createPaymentNotification(Payment $payment): void
    {
        $reservation = $payment->reservation()->with('client')->first();
        
        $typeMessages = [
            'aluguer' => 'O pagamento do aluguer',
            'caucao' => 'O pagamento da caução',
            'multa' => 'O pagamento da multa',
            'outros' => 'O pagamento',
        ];

        $typeMessage = $typeMessages[$payment->type] ?? 'O pagamento';
        $title = 'Pagamento Processado';
        $message = "{$typeMessage} da reserva #{$reservation->id} no valor de " . number_format($payment->amount, 2) . " AOA foi processado com sucesso.";
        $client = $reservation->client;

        // Criar notificação no banco de dados
        Notification::create([
            'user_id' => $reservation->client_id,
            'title' => $title,
            'message' => $message,
            'type' => 'pagamento',
            'is_read' => false,
            'created_at' => now(),
        ]);

        // Enviar email
        try {
            Mail::to($client->email)->send(
                new ReservationNotificationMail(
                    $reservation,
                    'pagamento',
                    $title,
                    $message
                )
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send payment email: ' . $e->getMessage());
        }

        // Enviar notificação push
        try {
            $client->notify(
                new ReservationStatusNotification(
                    $reservation,
                    'pagamento',
                    $title,
                    $message
                )
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send payment notification: ' . $e->getMessage());
        }
    }
}
