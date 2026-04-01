<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'cms_pages';

    protected $fillable = [
        'slug',
        'title',
        'body',
        'meta_description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** Slugs reserved for system routes (cannot be used for CMS pages). */
    public static function reservedSlugs(): array
    {
        return [
            'admin', 'account', 'login', 'register', 'logout', 'welcome', 'p',
            'flights', 'hotels', 'packages', 'buses', 'trains', 'cabs', 'visa', 'insurance',
            'blog', 'booking', 'bookings', 'payments', 'pay', 'guest',
            'faq', 'jobs', 'contact-us', 'contact_us',
            'storage', 'up', 'api', 'sanctum',
            'locale', 'auth', 'wishlist',
        ];
    }
}
