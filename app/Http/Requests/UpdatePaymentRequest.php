<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reservation_id' => ['sometimes', 'exists:reservations,id'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'type' => ['sometimes', 'in:aluguer,caucao,multa,outros'],
            'method' => ['sometimes', 'in:numerario,transferencia,pos,outros'],
            'status' => ['sometimes', 'in:pago,pendente,reembolsado'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}
