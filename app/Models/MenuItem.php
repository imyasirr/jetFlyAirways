<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
        if (str_starts_with($h, '#')) {
            return url('/').$h;
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

    /** @return Collection<int, MenuItem> */
    public static function treeFor(string $location)
    {
        $all = static::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->filter(fn (MenuItem $i) => ! $i->requires_auth || auth()->check())
            ->values();

        /** @var array<int|string, Collection<int, MenuItem>> $byParent */
        $byParent = $all->groupBy(fn (MenuItem $i) => $i->parent_id ?? 0);

        $build = function (MenuItem $node) use (&$build, $byParent): MenuItem {
            $children = ($byParent[$node->id] ?? collect())->values();
            $node->setRelation(
                'children',
                $children->map(fn (MenuItem $child) => $build($child))->values()
            );

            return $node;
        };

        return ($byParent[0] ?? collect())
            ->values()
            ->map(fn (MenuItem $root) => $build($root))
            ->values();
    }
}
