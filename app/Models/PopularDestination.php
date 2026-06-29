<?php

namespace App\Models;

use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PopularDestination extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'banner',
        'description',
        'details',
        'tag_line',
        'best_season',
        'package_destination',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (PopularDestination $destination) {
            if (! filled($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(PopularDestinationGallery::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function bannerUrl(): ?string
    {
        return PublicImageStorage::url($this->banner);
    }

    public function packagesUrl(): string
    {
        $dest = filled($this->package_destination) ? $this->package_destination : $this->name;

        return route('module.index', 'packages').'?destination='.urlencode($dest);
    }

    /**
     * @return array<string, mixed>
     */
    public function toListArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'tag_line' => $this->tag_line,
            'best_season' => $this->best_season,
            'banner_url' => $this->bannerUrl(),
            'package_destination' => $this->package_destination,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDetailArray(): array
    {
        return array_merge($this->toListArray(), [
            'description' => $this->description,
            'details' => $this->details,
            'banner' => $this->banner,
            'gallery' => $this->gallery->map(fn (PopularDestinationGallery $image) => $image->toApiArray())->values()->all(),
        ]);
    }
}
