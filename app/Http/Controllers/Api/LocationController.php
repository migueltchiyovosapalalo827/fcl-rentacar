<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        $locations = Location::query()->paginate($perPage);
        return response()->json($locations);
    }

    public function show(Location $location): JsonResponse
    {
        return response()->json($location);
    }

    public function store(StoreLocationRequest $request): JsonResponse
    {
        $location = Location::create($request->validated());
        return response()->json($location, 201);
    }

    public function update(UpdateLocationRequest $request, Location $location): JsonResponse
    {
        $location->update($request->validated());
        return response()->json($location);
    }

    public function destroy(Location $location): JsonResponse
    {
        $location->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
