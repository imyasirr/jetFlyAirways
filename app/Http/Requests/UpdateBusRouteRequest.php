<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'operator_name' => ['required', 'string', 'max:120'],
            'from_city' => ['required', 'string', 'max:100'],
            'to_city' => ['required', 'string', 'max:100'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after:departure_at'],
            'price' => ['required', 'numeric', 'min:0'],
            'seats_available' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
