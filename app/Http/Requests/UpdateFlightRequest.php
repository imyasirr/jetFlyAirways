<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'airline' => ['required', 'string', 'max:100'],
            'flight_number' => ['required', 'string', 'max:20'],
            'from_city' => ['required', 'string', 'max:100'],
            'to_city' => ['required', 'string', 'max:100'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after:departure_at'],
            'price' => ['required', 'numeric', 'min:0'],
            'stops' => ['required', 'integer', 'min:0', 'max:5'],
            'cabin_class' => ['required', 'string', 'max:50'],
            'seats_available' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
