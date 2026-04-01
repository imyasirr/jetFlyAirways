<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'tags',
        'author_name',
        'content',
        'is_featured',
        'publish_at',
        'cover_image',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'publish_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now());
    }

    public function getExcerptAttribute(): string
    {
        $plain = strip_tags((string) $this->content);

        return Str::limit($plain, 160);
    }
}
