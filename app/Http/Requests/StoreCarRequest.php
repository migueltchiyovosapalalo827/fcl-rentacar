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
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
