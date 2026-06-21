<?php

namespace App\Models;

use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PageBanner extends Model
{
    protected $fillable = [
        'page_key',
        'label',
        'image',
        'subtitle',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** @return array<string, string> page_key => admin label */
    public static function catalog(): array
    {
        return [
            'flights' => 'Flights (/flights)',
            'hotels' => 'Hotels (/hotels)',
            'packages' => 'Holiday packages (/packages)',
            'buses' => 'Buses (/buses)',
            'trains' => 'Trains (/trains)',
            'cabs' => 'Cabs (/cabs)',
            'visa' => 'Visa (/visa)',
            'insurance' => 'Insurance (/insurance)',
            'blog' => 'Travel blog (/blog)',
            'contact' => 'Contact us (/contact-us)',
        ];
    }

    /** Ensure every catalog entry has a database row (safe to call on each admin list load). */
    public static function syncCatalog(): void
    {
        if (! Schema::hasTable('page_banners')) {
            return;
        }

        foreach (static::catalog() as $pageKey => $label) {
            $row = static::query()->firstOrCreate(
                ['page_key' => $pageKey],
                ['label' => $label, 'is_active' => true]
            );

            if ($row->label !== $label) {
                $row->update(['label' => $label]);
            }
        }
    }

    public static function forKey(string $key): ?self
    {
        if (! Schema::hasTable('page_banners')) {
            return null;
        }

        return static::query()
            ->where('page_key', $key)
            ->where('is_active', true)
            ->first();
    }

    public function imageUrl(): ?string
    {
        return filled($this->image) ? PublicImageStorage::url($this->image) : null;
    }
}
