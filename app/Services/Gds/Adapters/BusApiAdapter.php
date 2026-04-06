<?php

namespace App\Services\Gds\Adapters;

use App\Models\ApiIntegration;
use App\Models\Booking;

class BusApiAdapter extends AbstractHttpIntegrationAdapter
{
    public function supports(string $service): bool
    {
        return $service === 'bus_api';
    }

    public function onBookingCreated(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'bus',
            'bus' => [
                'item_id' => $booking->module_item_id,
                'seats_requested' => $booking->travellers_count,
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'create-bus', $payload);
    }

    public function onPaymentCaptured(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'bus',
            'payment' => [
                'captured' => true,
                'payment_id' => $booking->razorpay_payment_id,
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'capture-bus-payment', $payload);
    }
}

