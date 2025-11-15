<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:255'],
            'role' => ['required', Rule::in(['gerente','caixa','motorista','cliente','tecnico'])],
        ];
    }
}


