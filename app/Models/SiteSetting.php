<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'topstrip_left',
        'support_phone',
        'support_email',
        'brand_name',
        'brand_tagline',
        'footer_about',
        'footer_badges',
        'footer_copyright_name',
        'social_facebook_url',
        'social_instagram_url',
        'social_linkedin_url',
        'social_twitter_url',
        'hero_image',
    ];
}
