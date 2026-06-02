<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApiIntegrationsRequest extends FormRequest
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
        return [
            'integrations' => ['required', 'array'],
            'integrations.*.service' => ['required', 'string'],
            'integrations.*.display_name' => ['required', 'string', 'max:120'],
            'integrations.*.base_url' => ['nullable', 'string', 'max:500'],
            'integrations.*.api_key' => ['nullable', 'string', 'max:500'],
            'integrations.*.api_secret' => ['nullable', 'string', 'max:500'],
            'integrations.*.notes' => ['nullable', 'string', 'max:5000'],
            'integrations.*.is_active' => ['nullable', 'boolean'],
        ];
    }
}
