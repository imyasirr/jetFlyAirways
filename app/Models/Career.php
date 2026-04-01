<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Career extends Model
{
    protected $fillable = [
        'job_title',
        'department',
        'location',
        'salary',
        'openings',
        'job_description',
        'required_skills',
        'apply_last_date',
        'is_hiring',
    ];

    protected function casts(): array
    {
        return [
            'apply_last_date' => 'date',
            'is_hiring' => 'boolean',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CareerApplication::class);
    }

    public function scopeOpenForApplications(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query
            ->where('is_hiring', true)
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('apply_last_date')->orWhereDate('apply_last_date', '>=', $today);
            });
    }
}
