<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'destination',
        'duration_days',
        'price',
        'offer_price',
        'itinerary',
        'details',
        'inclusions',
        'exclusions',
        'is_published',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'is_published' => 'boolean',
    ];
}
