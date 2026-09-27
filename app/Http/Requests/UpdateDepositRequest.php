<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepositRequest extends FormRequest
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
            'refunded' => ['sometimes', 'boolean'],
            'refunded_at' => ['nullable', 'date'],
        ];
    }
}
