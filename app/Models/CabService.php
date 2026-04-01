<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'from_location',
        'to_location',
        'base_fare',
        'per_km_rate',
        'is_active',
    ];

    protected $casts = [
        'base_fare' => 'decimal:2',
        'per_km_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
