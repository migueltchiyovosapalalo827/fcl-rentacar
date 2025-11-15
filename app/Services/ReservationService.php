<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations
    ) {}

    public function createReservation(array $data): Reservation
    {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        if ($end->lessThanOrEqualTo($start)) {
            throw new InvalidArgumentException('A data de fim deve ser posterior ao início.');
        }

        $car = Car::findOrFail($data['car_id']);
        $this->guardAgainstOverlapping($car->id, $start, $end);

        $days = max(1, $start->diffInDays($end));
        $total = $days * (float) $car->price_per_day;

        $data['total_amount'] = $total;
        $data['status'] = $data['status'] ?? 'pendente';

        return DB::transaction(function () use ($data) {
            return $this->reservations->create($data);
        });
    }

    public function updateStatus(Reservation $reservation, string $status): Reservation
    {
        $allowed = ['pendente','confirmada','ativa','concluida','cancelada'];
        if (!in_array($status, $allowed, true)) {
            throw new InvalidArgumentException('Status inválido.');
        }
        $reservation->status = $status;
        $reservation->save();
        return $reservation;
    }

    private function guardAgainstOverlapping(int $carId, Carbon $start, Carbon $end): void
    {
        $overlap = Reservation::query()
            ->where('car_id', $carId)
            ->whereIn('status', ['pendente','confirmada','ativa'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start)
                         ->where('end_date', '>=', $end);
                  });
            })
            ->exists();

        if ($overlap) {
            throw new InvalidArgumentException('Conflito de reserva para este veículo no período selecionado.');
        }
    }
}

<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Reservation;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly PaymentRepositoryInterface $payments,
    ) {}

    public function createReservation(array $data): Reservation
    {
        return DB::transaction(function () use ($data) {
            $reservation = $this->reservations->create($data);
            return $reservation;
        });
    }

    public function updateReservation(Reservation $reservation, array $data): Reservation
    {
        return DB::transaction(function () use ($reservation, $data) {
            return $this->reservations->update($reservation, $data);
        });
    }

    public function cancelReservation(Reservation $reservation): Reservation
    {
        if (! in_array($reservation->status, ['pendente','confirmada','ativa'])) {
            throw new InvalidArgumentException('Estado da reserva não permite cancelamento.');
        }
        return $this->updateReservation($reservation, ['status' => 'cancelada']);
    }

    public function confirmReservation(Reservation $reservation): Reservation
    {
        if ($reservation->status !== 'pendente') {
            throw new InvalidArgumentException('Apenas reservas pendentes podem ser confirmadas.');
        }
        return $this->updateReservation($reservation, ['status' => 'confirmada']);
    }

    public function activateReservation(Reservation $reservation): Reservation
    {
        if (! in_array($reservation->status, ['confirmada'])) {
            throw new InvalidArgumentException('A reserva deve estar confirmada para ser ativada.');
        }
        return $this->updateReservation($reservation, ['status' => 'ativa']);
    }

    public function completeReservation(Reservation $reservation): Reservation
    {
        if ($reservation->status !== 'ativa') {
            throw new InvalidArgumentException('A reserva deve estar ativa para ser concluída.');
        }
        return $this->updateReservation($reservation, ['status' => 'concluida']);
    }

    public function registerPayment(Reservation $reservation, array $paymentData)
    {
        $paymentData['reservation_id'] = $reservation->id;
        return $this->payments->create($paymentData);
    }

    public function registerDeposit(Reservation $reservation, float $amount): Deposit
    {
        return DB::transaction(function () use ($reservation, $amount) {
            return Deposit::create([
                'reservation_id' => $reservation->id,
                'amount' => $amount,
                'refunded' => false,
            ]);
        });
    }

    public function refundDeposit(Deposit $deposit): Deposit
    {
        if ($deposit->refunded) {
            return $deposit;
        }
        $deposit->update([
            'refunded' => true,
            'refunded_at' => now(),
        ]);
        return $deposit;
    }
}


