<?php

namespace App\Repositories\Eloquent;

use App\Models\Car;
use App\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarRepository implements CarRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Car::query()->paginate($perPage);
    }

    public function find(int $id): ?Car
    {
        return Car::find($id);
    }

    public function create(array $data): Car
    {
        return Car::create($data);
    }

    public function update(Car $car, array $data): Car
    {
        $car->update($data);
        return $car;
    }

    public function delete(Car $car): void
    {
        $car->delete();
    }
}
