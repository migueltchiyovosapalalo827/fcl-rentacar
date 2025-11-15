<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCars = Car::where('status', 'disponivel')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('public.home', compact('featuredCars'));
    }
}

