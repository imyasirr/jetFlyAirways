<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_code',
        'module',
        'module_item_id',
        'travel_date',
        'travellers_count',
        'total_amount',
        'status',
        'payment_status',
        'notes',
        'contact_name',
        'contact_email',
        'contact_phone',
        'razorpay_order_id',
        'razorpay_payment_id',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
