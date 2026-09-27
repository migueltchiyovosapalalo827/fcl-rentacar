<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes','exists:users,id'],
            'license_number' => ['sometimes','string','max:255'],
            'license_category' => ['sometimes','string','max:50'],
            'availability' => ['sometimes','in:livre,ocupado'],
        ];
    }
}
