<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    use HasFactory;

    protected $fillable = [
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
