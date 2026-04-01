<?php

namespace App\Contracts;

use App\Models\Booking;

/**
 * Placeholder for future flight/hotel GDS or aggregator API integration.
 */
interface GdsBookingClient
{
    /** @return array<string, mixed> */
    public function searchFlights(array $criteria): array;

    /** @return array<string, mixed> */
    public function searchHotels(array $criteria): array;

    /** Called after a booking row is created (inventory held in your DB). */
    public function recordBookingCreated(Booking $booking): void;

    /** Called after Razorpay (or other) payment is verified as paid. */
    public function recordPaymentCaptured(Booking $booking): void;
}
