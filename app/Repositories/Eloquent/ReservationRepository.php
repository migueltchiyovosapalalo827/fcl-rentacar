<?php

namespace App\Repositories\Eloquent;

use App\Models\Reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Reservation::with(['client', 'car', 'driver', 'pickupLocation', 'dropoffLocation'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Reservation
    {
        return Reservation::with(['client', 'car', 'driver', 'pickupLocation', 'dropoffLocation', 'payments', 'deposit'])
            ->find($id);
    }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation;
    }

    public function delete(Reservation $reservation): void
    {
        $reservation->delete();
    }
}

<?php

namespace App\Repositories\Eloquent;

use App\Models\Reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Reservation::query()
            ->with(['client', 'car', 'driver', 'pickupLocation', 'dropoffLocation'])
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Reservation
    {
        return Reservation::with(['client', 'car', 'driver', 'pickupLocation', 'dropoffLocation'])->find($id);
    }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation;
    }

    public function delete(Reservation $reservation): void
    {
        $reservation->delete();
    }
}


