<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Deposit;
use App\Models\Reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public const DRIVER_DAILY_FEE = 50.0;

    public function __construct(
        private readonly ReservationRepositoryInterface $reservations
    ) {}

    public function createReservation(array $data): Reservation
    {
        [$start, $end] = $this->parsePeriod($data['start_date'], $data['end_date']);

        $car = Car::findOrFail($data['car_id']);
        $this->guardAgainstOverlapping($car->id, $start, $end);

        $data['total_amount'] = $this->calculateTotal($car, $start, $end, !empty($data['with_driver']));
        $data['status'] = $data['status'] ?? 'pendente';
        $data['with_driver'] = !empty($data['with_driver']);

        return DB::transaction(function () use ($data) {
            return $this->reservations->create($data);
        });
    }

    public function updateReservation(Reservation $reservation, array $data): Reservation
    {
        $carId = $data['car_id'] ?? $reservation->car_id;
        $start = Carbon::parse($data['start_date'] ?? $reservation->start_date);
        $end = Carbon::parse($data['end_date'] ?? $reservation->end_date);
        $periodChanged = array_key_exists('car_id', $data)
            || array_key_exists('start_date', $data)
            || array_key_exists('end_date', $data);

        if ($end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'end_date' => 'A data de fim deve ser posterior ao início.',
            ]);
        }

        if ($periodChanged) {
            $this->guardAgainstOverlapping((int) $carId, $start, $end, $reservation->id);
        }

        if ($periodChanged || array_key_exists('with_driver', $data)) {
            $car = Car::findOrFail($carId);
            $withDriver = array_key_exists('with_driver', $data) ? !empty($data['with_driver']) : (bool) $reservation->with_driver;
            $data['total_amount'] = $this->calculateTotal($car, $start, $end, $withDriver);
        }

        return DB::transaction(function () use ($reservation, $data) {
            return $this->reservations->update($reservation, $data);
        });
    }

    public function updateStatus(Reservation $reservation, string $status): Reservation
    {
        return match ($status) {
            'confirmada' => $this->confirmReservation($reservation),
            'ativa' => $this->activateReservation($reservation),
            'concluida' => $this->completeReservation($reservation),
            'cancelada' => $this->cancelReservation($reservation),
            'pendente' => $this->updateReservation($reservation, ['status' => 'pendente']),
            default => throw ValidationException::withMessages([
                'status' => 'Status inválido.',
            ]),
        };
    }

    public function cancelReservation(Reservation $reservation): Reservation
    {
        if (! in_array($reservation->status, ['pendente', 'confirmada', 'ativa'], true)) {
            throw ValidationException::withMessages([
                'status' => 'Estado da reserva não permite cancelamento.',
            ]);
        }

        return $this->updateReservation($reservation, ['status' => 'cancelada']);
    }

    public function confirmReservation(Reservation $reservation): Reservation
    {
        if ($reservation->status !== 'pendente') {
            throw ValidationException::withMessages([
                'status' => 'Apenas reservas pendentes podem ser confirmadas.',
            ]);
        }

        return $this->updateReservation($reservation, ['status' => 'confirmada']);
    }

    public function activateReservation(Reservation $reservation): Reservation
    {
        if ($reservation->status !== 'confirmada') {
            throw ValidationException::withMessages([
                'status' => 'A reserva deve estar confirmada para ser ativada.',
            ]);
        }

        return $this->updateReservation($reservation, ['status' => 'ativa']);
    }

    public function completeReservation(Reservation $reservation): Reservation
    {
        if ($reservation->status !== 'ativa') {
            throw ValidationException::withMessages([
                'status' => 'A reserva deve estar ativa para ser concluída.',
            ]);
        }

        return $this->updateReservation($reservation, ['status' => 'concluida']);
    }

    public function registerDeposit(Reservation $reservation, float $amount): Deposit
    {
        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'Valor da caução inválido.',
            ]);
        }

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

    public function countDays(Carbon $start, Carbon $end): int
    {
        return max(1, (int) ceil($start->floatDiffInDays($end)));
    }

    public function calculateTotal(Car $car, Carbon $start, Carbon $end, bool $withDriver): float
    {
        $days = $this->countDays($start, $end);
        $total = $days * (float) $car->price_per_day;

        if ($withDriver) {
            $total += self::DRIVER_DAILY_FEE * $days;
        }

        return $total;
    }

    private function parsePeriod(mixed $startDate, mixed $endDate): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'end_date' => 'A data de fim deve ser posterior ao início.',
            ]);
        }

        return [$start, $end];
    }

    private function guardAgainstOverlapping(int $carId, Carbon $start, Carbon $end, ?int $ignoreReservationId = null): void
    {
        $overlap = Reservation::query()
            ->where('car_id', $carId)
            ->whereIn('status', ['pendente', 'confirmada', 'ativa'])
            ->when($ignoreReservationId, fn ($q) => $q->where('id', '!=', $ignoreReservationId))
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'car_id' => 'Conflito de reserva para este veículo no período selecionado.',
            ]);
        }
    }
}
