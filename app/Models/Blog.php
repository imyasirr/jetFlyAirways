<?php

namespace App\Models;

use App\Support\PublicImageStorage;
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

    public function getCoverUrlAttribute(): ?string
    {
        return PublicImageStorage::url($this->cover_image);
    }

    /**
     * Fix relative & storage paths in HTML so images work on /blog/{slug}.
     */
    public function getRenderedContentAttribute(): string
    {
        return self::rewriteContentImageUrls($this->content ?? '');
    }

    public static function rewriteContentImageUrls(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        $out = preg_replace_callback(
            '#<img\s([^>]*?)\bsrc\s*=\s*(["\'])([^"\']*)\2#i',
            static function (array $m): string {
                $attrs = $m[1];
                $q = $m[2];
                $src = trim($m[3]);
                if ($src === '' || preg_match('#^(https?:)?//#i', $src) || str_starts_with($src, 'data:')) {
                    return $m[0];
                }
                if (str_starts_with($src, '/')) {
                    return $m[0];
                }
                $normalized = str_replace('\\', '/', $src);
                if (str_starts_with($normalized, 'storage/')) {
                    $abs = '/'.$normalized;
                } else {
                    $resolved = PublicImageStorage::url(ltrim($normalized, '/'));
                    $abs = $resolved ?? $src;
                }

                return '<img '.$attrs.'src='.$q.$abs.$q;
            },
            $html
        );

        return is_string($out) ? $out : $html;
    }
}
