<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reservation_id' => ['required', 'exists:reservations,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['nullable', 'in:aluguer,caucao,multa,outros'],
            'method' => ['nullable', 'in:numerario,transferencia,pos,outros'],
            'status' => ['nullable', 'in:pago,pendente,reembolsado'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}
