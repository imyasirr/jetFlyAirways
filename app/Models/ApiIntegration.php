<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiIntegration extends Model
{
    protected $fillable = [
        'service',
        'display_name',
        'base_url',
        'api_key',
        'api_secret',
        'is_active',
        'notes',
        'last_checked_at',
        'last_check_status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_checked_at' => 'datetime',
    ];
}

