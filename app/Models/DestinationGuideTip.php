<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationGuideTip extends Model
{
    protected $fillable = [
        'title',
        'body',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
