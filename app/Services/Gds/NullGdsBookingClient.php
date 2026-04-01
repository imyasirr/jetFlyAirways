<?php

namespace App\Services\Gds;

use App\Contracts\GdsBookingClient;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class NullGdsBookingClient implements GdsBookingClient
{
    public function searchFlights(array $criteria): array
    {
        return ['status' => 'not_configured', 'message' => 'Connect a GDS or supplier API in config.'];
    }

    public function searchHotels(array $criteria): array
    {
        return ['status' => 'not_configured', 'message' => 'Connect a GDS or supplier API in config.'];
    }

    public function recordBookingCreated(Booking $booking): void
    {
        Log::debug('[GDS stub] booking created', ['id' => $booking->id, 'module' => $booking->module]);
    }

    public function recordPaymentCaptured(Booking $booking): void
    {
        Log::debug('[GDS stub] payment captured', ['id' => $booking->id, 'module' => $booking->module]);
    }
}
