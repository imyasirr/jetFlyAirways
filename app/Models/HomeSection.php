<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'partial_key',
        'admin_label',
        'sort_order',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** @return list<string> */
    public static function allowedPartialKeys(): array
    {
        return [
            'offers',
            'destinations',
            'flights',
            'hotels',
            'packages',
            'testimonials',
            'services',
            'trust_row',
        ];
    }
}
