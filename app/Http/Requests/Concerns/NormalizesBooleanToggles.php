<?php

namespace App\Http\Requests\Concerns;

trait NormalizesBooleanToggles
{
    protected function prepareForValidation(): void
    {
        foreach (['is_active', 'is_published', 'is_featured', 'show_button', 'show_tags'] as $field) {
            if (! $this->has($field)) {
                continue;
            }

            $value = $this->input($field);
            if (is_array($value)) {
                $value = end($value);
            }

            $this->merge([
                $field => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }
    }
}
