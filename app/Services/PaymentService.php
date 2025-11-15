<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    public function registerPayment(Reservation $reservation, array $data): Payment
    {
        if (($data['amount'] ?? 0) <= 0) {
            throw new InvalidArgumentException('Valor do pagamento inválido.');
        }

        return DB::transaction(function () use ($reservation, $data) {
            /** @var Payment $payment */
            $payment = $reservation->payments()->create([
                'amount' => $data['amount'],
                'type' => $data['type'] ?? 'aluguer',
                'paid_at' => $data['paid_at'] ?? now(),
                'method' => $data['method'] ?? 'numerario',
                'status' => $data['status'] ?? 'pago',
            ]);
            return $payment;
        });
    }
}


