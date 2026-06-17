<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationGuideSetting extends Model
{
    protected $fillable = [
        'intro',
        'spots_heading',
        'spots_subheading',
        'tips_heading',
        'callout_title',
        'callout_body',
        'callout_link',
        'callout_link_label',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'intro' => '',
            'spots_heading' => 'Trending destinations',
            'tips_heading' => 'Quick planning tips',
        ]);
    }
}
