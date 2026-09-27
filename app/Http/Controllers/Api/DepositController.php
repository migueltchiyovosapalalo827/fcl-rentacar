<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use App\Models\Deposit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        $deposits = Deposit::with('reservation')->latest()->paginate($perPage);

        return response()->json($deposits);
    }

    public function show(Deposit $deposit): JsonResponse
    {
        $deposit->load('reservation');

        return response()->json($deposit);
    }

    public function store(StoreDepositRequest $request): JsonResponse
    {
        $deposit = Deposit::create($request->validated());

        return response()->json($deposit, 201);
    }

    public function update(UpdateDepositRequest $request, Deposit $deposit): JsonResponse
    {
        $deposit->update($request->validated());

        return response()->json($deposit);
    }

    public function destroy(Deposit $deposit): JsonResponse
    {
        $deposit->delete();

        return response()->json(null, 204);
    }
}
