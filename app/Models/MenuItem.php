<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'parent_id',
        'location',
        'label',
        'href',
        'sort_order',
        'is_active',
        'open_new_tab',
        'requires_auth',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'open_new_tab' => 'boolean',
            'requires_auth' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function resolvedUrl(): string
    {
        if (! $this->href) {
            return '#';
        }
        $h = trim($this->href);
        if (str_starts_with($h, 'http://') || str_starts_with($h, 'https://') || str_starts_with($h, '//')) {
            return $h;
        }

        return url($h);
    }

    public function isCurrent(): bool
    {
        if (! $this->href) {
            return false;
        }
        $h = trim(trim($this->href), '/');
        $cur = trim(request()->path(), '/');
        if ($h === '' || $h === '/') {
            return $cur === '';
        }

        return $cur === $h;
    }

    /** @return \Illuminate\Support\Collection<int, MenuItem> */
    public static function treeFor(string $location)
    {
        return static::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->get()
            ->filter(function (MenuItem $item) {
                if ($item->requires_auth && ! auth()->check()) {
                    return false;
                }

                return true;
            })
            ->map(function (MenuItem $item) {
                $item->setRelation(
                    'children',
                    $item->children->filter(function (MenuItem $child) {
                        if ($child->requires_auth && ! auth()->check()) {
                            return false;
                        }

                        return true;
                    })->values()
                );

                return $item;
            })
            ->values();
    }
}
