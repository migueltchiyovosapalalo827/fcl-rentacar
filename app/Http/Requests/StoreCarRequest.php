<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand' => ['required','string','max:255'],
            'model' => ['required','string','max:255'],
            'plate_number' => ['required','string','max:50','unique:cars,plate_number'],
            'price_per_day' => ['required','numeric','min:0'],
            'status' => ['nullable','in:disponivel,alugado,manutencao,inativo'],
            'year' => ['nullable','integer','min:1900','max:2100'],
            'km' => ['nullable','integer','min:0'],
            'image' => ['nullable','string','max:2048'],
            'description' => ['nullable','string','max:2000'],
            'color' => ['nullable','string','max:50'],
            'category' => ['nullable','in:economico,compacto,sedan,suv,pickup,luxo,van'],
            'seats' => ['nullable','integer','min:1','max:50'],
            'doors' => ['nullable','integer','min:2','max:6'],
            'luggage_capacity' => ['nullable','integer','min:0','max:20'],
            'fuel_type' => ['nullable','in:gasolina,diesel,hibrido,eletrico'],
            'transmission' => ['nullable','in:manual,automatica'],
            'air_conditioning' => ['nullable','boolean'],
            'photos' => ['nullable','array'],
            'photos.*' => ['string','max:2048'],
        ];
    }
}
