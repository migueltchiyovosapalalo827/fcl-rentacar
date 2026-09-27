<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $carId = $this->route('car')?->id ?? null;
        return [
            'brand' => ['sometimes','string','max:255'],
            'model' => ['sometimes','string','max:255'],
            'plate_number' => ['sometimes','string','max:50', Rule::unique('cars','plate_number')->ignore($carId)],
            'price_per_day' => ['sometimes','numeric','min:0'],
            'status' => ['sometimes','in:disponivel,alugado,manutencao,inativo'],
            'year' => ['sometimes','integer','min:1900','max:2100'],
            'km' => ['sometimes','integer','min:0'],
            'image' => ['sometimes','string','max:2048'],
            'description' => ['sometimes','nullable','string','max:2000'],
            'color' => ['sometimes','nullable','string','max:50'],
            'category' => ['sometimes','nullable','in:economico,compacto,sedan,suv,pickup,luxo,van'],
            'seats' => ['sometimes','nullable','integer','min:1','max:50'],
            'doors' => ['sometimes','nullable','integer','min:2','max:6'],
            'luggage_capacity' => ['sometimes','nullable','integer','min:0','max:20'],
            'fuel_type' => ['sometimes','nullable','in:gasolina,diesel,hibrido,eletrico'],
            'transmission' => ['sometimes','nullable','in:manual,automatica'],
            'air_conditioning' => ['sometimes','boolean'],
            'photos' => ['sometimes','nullable','array'],
            'photos.*' => ['string','max:2048'],
        ];
    }
}
