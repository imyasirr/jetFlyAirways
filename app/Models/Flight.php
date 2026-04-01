<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline',
        'flight_number',
        'from_city',
        'to_city',
        'departure_at',
        'arrival_at',
        'price',
        'stops',
        'cabin_class',
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
