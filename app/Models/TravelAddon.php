<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelAddon extends Model
{
    public const CATEGORY_VISA = 'visa';

    public const CATEGORY_INSURANCE = 'insurance';

    protected $fillable = [
        'category',
        'name',
        'summary',
        'description',
        'price',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /** @return list<string> */
    public static function categories(): array
    {
        return [self::CATEGORY_VISA, self::CATEGORY_INSURANCE];
    }
}
