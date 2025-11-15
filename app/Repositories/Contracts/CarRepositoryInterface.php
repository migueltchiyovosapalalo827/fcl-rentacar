<?php

namespace App\Repositories\Contracts;

use App\Models\Car;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CarRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Car;
    public function create(array $data): Car;
    public function update(Car $car, array $data): Car;
    public function delete(Car $car): void;
}

<?php

namespace App\Repositories\Contracts;

use App\Models\Car;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CarRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Car;
    public function create(array $data): Car;
    public function update(Car $car, array $data): Car;
    public function delete(Car $car): void;
}


