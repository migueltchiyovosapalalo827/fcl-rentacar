<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepositRequest extends FormRequest
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
            'refunded' => ['nullable', 'boolean'],
            'refunded_at' => ['nullable', 'date'],
        ];
    }
}
