<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'meta_title',
        'meta_description',
        'keywords',
        'canonical_url',
        'slug',
        'og_title',
        'og_description',
        'og_image',
        'schema_markup',
        'robots',
    ];

    public const SITE_ENTITY_TYPE = 'site';

    public const SITE_ENTITY_ID = 0;
}
