<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required','exists:users,id'],
            'car_id' => ['required','exists:cars,id'],
            'driver_id' => ['nullable','exists:drivers,id'],
            'pickup_location_id' => ['required','exists:locations,id'],
            'dropoff_location_id' => ['required','exists:locations,id'],
            'start_date' => ['required','date'],
            'end_date' => ['required','date','after:start_date'],
            'purpose' => ['required','in:negocios,casamento,passeio,trabalho,outros'],
            'with_driver' => ['boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
