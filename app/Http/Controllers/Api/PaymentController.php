<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentRepositoryInterface $payments) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);

        return response()->json($this->payments->paginate($perPage));
    }

    public function show(Payment $payment): JsonResponse
    {
        $payment->load('reservation');

        return response()->json($payment);
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = $this->payments->create($request->validated());

        return response()->json($payment, 201);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): JsonResponse
    {
        $payment = $this->payments->update($payment, $request->validated());

        return response()->json($payment);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $this->payments->delete($payment);

        return response()->json(null, 204);
    }
}
