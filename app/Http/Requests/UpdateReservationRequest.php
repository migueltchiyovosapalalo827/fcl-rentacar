<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['sometimes','exists:users,id'],
            'car_id' => ['sometimes','exists:cars,id'],
            'driver_id' => ['nullable','exists:drivers,id'],
            'pickup_location_id' => ['sometimes','exists:locations,id'],
            'dropoff_location_id' => ['sometimes','exists:locations,id'],
            'start_date' => ['sometimes','date'],
            'end_date' => ['sometimes','date','after:start_date'],
            'purpose' => ['sometimes','in:negocios,casamento,passeio,trabalho,outros'],
            'with_driver' => ['sometimes','boolean'],
            'status' => ['sometimes','in:pendente,confirmada,ativa,concluida,cancelada'],
            'total_amount' => ['sometimes','numeric','min:0'],
        ];
    }
}
