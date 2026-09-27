<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'address' => ['required','string','max:255'],
            'latitude' => ['required','numeric'],
            'longitude' => ['required','numeric'],
            'active' => ['nullable','boolean'],
        ];
    }
}
