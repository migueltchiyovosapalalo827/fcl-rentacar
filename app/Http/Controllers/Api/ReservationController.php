<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use App\Services\PaymentService;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly ReservationService $reservationService,
        private readonly PaymentService $paymentService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        return response()->json($this->reservations->paginate($perPage));
    }

    public function show(Reservation $reservation): JsonResponse
    {
        $reservation->load(['client', 'car', 'driver', 'pickupLocation', 'dropoffLocation', 'payments', 'deposit']);
        return response()->json($reservation);
    }

    public function store(StoreReservationRequest $request): JsonResponse
    {
        $reservation = $this->reservationService->createReservation($request->validated());
        return response()->json($reservation, 201);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation): JsonResponse
    {
        $reservation = $this->reservations->update($reservation, $request->validated());
        return response()->json($reservation);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $this->reservations->delete($reservation);
        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, Reservation $reservation): JsonResponse
    {
        $data = $request->validate(['status' => 'required|in:pendente,confirmada,ativa,concluida,cancelada']);
        $updated = $this->reservationService->updateStatus($reservation, $data['status']);
        return response()->json($updated);
    }

    public function pay(Request $request, Reservation $reservation): JsonResponse
    {
        $data = $request->validate([
            'amount' => ['required','numeric','min:0.01'],
            'type' => ['nullable','in:aluguer,caucao,multa,outros'],
            'method' => ['nullable','in:numerario,transferencia,pos,outros'],
            'status' => ['nullable','in:pago,pendente,reembolsado'],
            'paid_at' => ['nullable','date'],
        ]);
        $payment = $this->paymentService->registerPayment($reservation, $data);
        return response()->json($payment, 201);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
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
