<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $featuredCars = Car::where('status', 'disponivel')
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->map(fn (Car $car) => $car->toPublicArray())
            ->values();

        return Inertia::render('Public/Home', [
            'featuredCars' => $featuredCars,
        ]);
    }
}
