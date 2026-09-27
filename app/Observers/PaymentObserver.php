<?php

namespace App\Observers;

use App\Mail\ReservationNotificationMail;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentObserver
{
    public function updated(Payment $payment): void
    {
        if ($payment->wasChanged('status') && $payment->status === 'pago') {
            $this->createPaymentNotification($payment);
        }
    }

    public function created(Payment $payment): void
    {
        if ($payment->status === 'pago') {
            $this->createPaymentNotification($payment);
        }
    }

    protected function createPaymentNotification(Payment $payment): void
    {
        $reservation = $payment->reservation()->with(['client', 'car', 'pickupLocation', 'dropoffLocation'])->first();

        if (! $reservation || ! $reservation->client) {
            return;
        }

        $typeMessages = [
            'aluguer' => 'O pagamento do aluguer',
            'caucao' => 'O pagamento da caução',
            'multa' => 'O pagamento da multa',
            'outros' => 'O pagamento',
        ];

        $typeMessage = $typeMessages[$payment->type] ?? 'O pagamento';
        $title = 'Pagamento Processado';
        $message = "{$typeMessage} da reserva #{$reservation->id} no valor de ".number_format((float) $payment->amount, 2).' AOA foi processado com sucesso.';
        $client = $reservation->client;

        Notification::create([
            'user_id' => $reservation->client_id,
            'title' => $title,
            'message' => $message,
            'type' => 'pagamento',
            'is_read' => false,
            'created_at' => now(),
        ]);

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
            Log::error('Failed to send payment email: '.$e->getMessage());
        }
    }
}
