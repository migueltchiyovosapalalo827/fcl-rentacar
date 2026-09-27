<?php

namespace App\Repositories\Contracts;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReservationRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Reservation;
    public function create(array $data): Reservation;
    public function update(Reservation $reservation, array $data): Reservation;
    public function delete(Reservation $reservation): void;
}
