<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(private readonly CarRepositoryInterface $cars) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        return response()->json($this->cars->paginate($perPage));
    }

    public function show(Car $car): JsonResponse
    {
        return response()->json($car);
    }

    public function store(StoreCarRequest $request): JsonResponse
    {
        $car = $this->cars->create($request->validated());
        return response()->json($car, 201);
    }

    public function update(UpdateCarRequest $request, Car $car): JsonResponse
    {
        $car = $this->cars->update($car, $request->validated());
        return response()->json($car);
    }

    public function destroy(Car $car): JsonResponse
    {
        $this->cars->delete($car);
        return response()->json(null, 204);
    }
}
