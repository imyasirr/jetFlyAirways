<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use Concerns\HasInventorySlug;
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'city',
        'location',
        'star_rating',
        'price_per_night',
        'amenities',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'price_per_night' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
