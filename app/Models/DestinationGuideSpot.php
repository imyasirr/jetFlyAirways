<?php

namespace App\Models;

use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Model;

class DestinationGuideSpot extends Model
{
    protected $fillable = [
        'name',
        'tag_line',
        'best_season',
        'image',
        'package_destination',
        'link_url',
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

    public function imageUrl(): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return PublicImageStorage::url($this->image);
    }

    public function href(): string
    {
        if (filled($this->link_url)) {
            return $this->link_url;
        }

        if (filled($this->package_destination)) {
            return route('module.index', 'packages').'?destination='.urlencode($this->package_destination);
        }

        return route('module.index', 'packages');
    }

    public function subtitle(): string
    {
        return collect([$this->tag_line, $this->best_season])->filter()->implode(' · ');
    }
}
