<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'message' => ['sometimes', 'string'],
            'type' => ['sometimes', 'in:reserva,pagamento,alerta,sistema'],
            'is_read' => ['sometimes', 'boolean'],
        ];
    }
}
