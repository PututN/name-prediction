<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PredictNameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', "regex:/^[\pL\s'-]+$/u"],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, tanda petik, dan tanda hubung.',
        ];
    }
}
