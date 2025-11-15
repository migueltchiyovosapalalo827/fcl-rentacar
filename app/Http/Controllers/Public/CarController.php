<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::where('status', 'disponivel')->paginate(12);

        return view('public.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        return view('public.cars.show', compact('car'));
    }
}

