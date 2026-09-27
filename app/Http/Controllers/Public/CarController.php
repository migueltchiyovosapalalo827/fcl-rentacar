<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Inertia\Inertia;
use Inertia\Response;

class CarController extends Controller
{
    public function index(): Response
    {
        $cars = Car::query()
            ->where('status', '!=', 'inativo')
            ->orderByRaw("CASE status WHEN 'disponivel' THEN 0 WHEN 'alugado' THEN 1 ELSE 2 END")
            ->orderBy('brand')
            ->paginate(12)
            ->through(fn (Car $car) => $car->toPublicArray());

        return Inertia::render('Public/Cars/Index', [
            'cars' => $cars,
        ]);
    }

    public function show(Car $car): Response
    {
        return Inertia::render('Public/Cars/Show', [
            'car' => $car->toPublicArray(withCurrentRental: true),
        ]);
    }
}
