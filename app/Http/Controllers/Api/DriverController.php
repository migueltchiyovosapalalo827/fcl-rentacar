<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        $drivers = Driver::with('user')->paginate($perPage);
        return response()->json($drivers);
    }

    public function show(Driver $driver): JsonResponse
    {
        $driver->load('user');
        return response()->json($driver);
    }

    public function store(StoreDriverRequest $request): JsonResponse
    {
        $driver = Driver::create($request->validated());
        return response()->json($driver, 201);
    }

    public function update(UpdateDriverRequest $request, Driver $driver): JsonResponse
    {
        $driver->update($request->validated());
        return response()->json($driver);
    }

    public function destroy(Driver $driver): JsonResponse
    {
        $driver->delete();
        return response()->json(null, 204);
    }
}

