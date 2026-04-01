<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCabServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_type' => ['required', 'string', 'max:80'],
            'from_location' => ['required', 'string', 'max:100'],
            'to_location' => ['nullable', 'string', 'max:100'],
            'base_fare' => ['required', 'numeric', 'min:0'],
            'per_km_rate' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
