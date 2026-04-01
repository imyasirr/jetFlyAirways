<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:80'],
            'name' => ['required', 'string', 'max:160'],
            'destination' => ['required', 'string', 'max:100'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'price' => ['required', 'numeric', 'min:0'],
            'offer_price' => ['nullable', 'numeric', 'min:0'],
            'itinerary' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'inclusions' => ['nullable', 'string'],
            'exclusions' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
