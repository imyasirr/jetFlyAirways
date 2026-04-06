<?php

namespace App\Services\Gds\Adapters;

use App\Models\ApiIntegration;
use App\Models\Booking;

interface ModuleIntegrationAdapter
{
    public function supports(string $service): bool;

    /**
     * Sync booking-created event to provider and return human status.
     */
    public function onBookingCreated(ApiIntegration $integration, Booking $booking): string;

    /**
     * Sync payment-captured event to provider and return human status.
     */
    public function onPaymentCaptured(ApiIntegration $integration, Booking $booking): string;
}

