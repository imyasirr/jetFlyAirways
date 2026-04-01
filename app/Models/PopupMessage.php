<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PopupMessage extends Model
{
    protected $fillable = [
        'title',
        'message',
        'image',
        'button_text',
        'redirect_link',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActiveForDisplay(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
            })
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
            })
            ->orderByDesc('id');
    }
}
