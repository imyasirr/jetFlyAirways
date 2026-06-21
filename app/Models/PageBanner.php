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
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_system' => 'boolean',
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

    public static function cmsPageKey(string $slug): string
    {
        return 'cms.'.$slug;
    }

    public static function cmsSlugFromKey(string $pageKey): ?string
    {
        if (! str_starts_with($pageKey, 'cms.')) {
            return null;
        }

        $slug = substr($pageKey, 4);

        return $slug !== '' ? $slug : null;
    }

    public function linkedCmsPage(): ?Page
    {
        $slug = static::cmsSlugFromKey($this->page_key);

        if ($slug === null || ! Schema::hasTable('cms_pages')) {
            return null;
        }

        return Page::query()->where('slug', $slug)->first();
    }

    public function isCustomCmsBanner(): bool
    {
        return ! $this->is_system && static::cmsSlugFromKey($this->page_key) !== null;
    }

    /** Ensure built-in module/blog/contact rows exist. */
    public static function syncCatalog(): void
    {
        if (! Schema::hasTable('page_banners')) {
            return;
        }

        foreach (static::catalog() as $pageKey => $label) {
            $row = static::query()->firstOrCreate(
                ['page_key' => $pageKey],
                ['label' => $label, 'is_active' => true, 'is_system' => true]
            );

            $updates = [];
            if ($row->label !== $label) {
                $updates['label'] = $label;
            }
            if (! $row->is_system) {
                $updates['is_system'] = true;
            }
            if ($updates !== []) {
                $row->update($updates);
            }
        }
    }

    /** Mirror CMS pages into this list so banners can be managed here. */
    public static function syncCmsPages(): void
    {
        if (! Schema::hasTable('page_banners') || ! Schema::hasTable('cms_pages')) {
            return;
        }

        Page::query()->orderBy('title')->each(function (Page $page): void {
            static::syncForCmsPage($page);
        });
    }

    public static function syncForCmsPage(Page $page): self
    {
        return static::query()->updateOrCreate(
            ['page_key' => static::cmsPageKey($page->slug)],
            [
                'label' => $page->title.' (/p/'.$page->slug.')',
                'is_system' => false,
            ]
        );
    }

    public static function deleteForCmsPage(Page $page): void
    {
        if (! Schema::hasTable('page_banners')) {
            return;
        }

        static::query()
            ->where('page_key', static::cmsPageKey($page->slug))
            ->delete();
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
