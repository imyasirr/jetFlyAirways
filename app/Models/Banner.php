<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'tags',
        'show_tags',
        'image',
        'link',
        'button_text',
        'show_button',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'show_tags' => 'boolean',
            'show_button' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /** @return array<int, string> */
    public function tagList(): array
    {
        return collect($this->tags ?? [])
            ->map(fn ($tag) => is_string($tag) ? trim($tag) : '')
            ->filter()
            ->values()
            ->all();
    }
}
