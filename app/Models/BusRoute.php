<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    use Concerns\HasInventorySlug;
    use HasFactory;

    protected $fillable = [
        'slug',
        'operator_name',
        'from_city',
        'to_city',
        'departure_at',
        'arrival_at',
        'price',
        'seats_available',
        'is_active',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
