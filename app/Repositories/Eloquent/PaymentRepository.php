<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Payment::with('reservation')->latest()->paginate($perPage);
    }

    public function find(int $id): ?Payment
    {
        return Payment::with('reservation')->find($id);
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function update(Payment $payment, array $data): Payment
    {
        $payment->update($data);
        return $payment;
    }

    public function delete(Payment $payment): void
    {
        $payment->delete();
    }
}
