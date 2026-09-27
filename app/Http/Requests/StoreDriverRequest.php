<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required','exists:users,id'],
            'license_number' => ['required','string','max:255'],
            'license_category' => ['required','string','max:50'],
            'availability' => ['nullable','in:livre,ocupado'],
        ];
    }
}
