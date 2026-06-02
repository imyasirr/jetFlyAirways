<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $slug = (string) $this->route('module', '');

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'travellers' => ['required', 'integer', 'min:1', 'max:20'],
            'travel_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'save_traveller' => ['nullable', 'boolean'],
            'coupon_code' => ['nullable', 'string', 'max:40'],
        ];

        if ($slug === 'flights') {
            $rules['trip_type'] = ['required', 'in:one_way,round_trip,multi_city'];
            $rules['return_date'] = ['nullable', 'date', 'after_or_equal:travel_date'];
            $rules['seat_preference'] = ['nullable', 'string', 'max:80'];
            $rules['meal_preference'] = ['nullable', 'string', 'max:120'];
            $rules['multi_city_notes'] = ['nullable', 'string', 'max:2000'];
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if ((string) $this->route('module') !== 'flights') {
                return;
            }
            if ($this->string('trip_type')->toString() === 'round_trip') {
                if (! $this->filled('return_date')) {
                    $v->errors()->add('return_date', 'Return date is required for a round trip.');
                }
            }
        });
    }
}
