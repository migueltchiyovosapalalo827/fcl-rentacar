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
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
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
