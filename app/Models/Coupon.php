<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'valid_from',
        'valid_to',
        'max_usage',
        'used_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_to' => 'date',
            'discount_value' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function isCurrentlyValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }
        $today = now()->toDateString();
        if ($this->valid_from && $this->valid_from->toDateString() > $today) {
            return false;
        }
        if ($this->valid_to && $this->valid_to->toDateString() < $today) {
            return false;
        }
        if ($this->max_usage !== null && $this->used_count >= $this->max_usage) {
            return false;
        }

        return true;
    }
}
