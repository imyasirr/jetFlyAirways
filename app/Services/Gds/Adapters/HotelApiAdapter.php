<?php

namespace App\Services\Gds\Adapters;

use App\Models\ApiIntegration;
use App\Models\Booking;

class HotelApiAdapter extends AbstractHttpIntegrationAdapter
{
    public function supports(string $service): bool
    {
        return $service === 'hotel_api';
    }

    public function onBookingCreated(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'hotel',
            'hotel' => [
                'item_id' => $booking->module_item_id,
                'rooms' => max(1, $booking->travellers_count),
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'create-hotel', $payload);
    }

    public function onPaymentCaptured(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'hotel',
            'payment' => [
                'captured' => true,
                'payment_id' => $booking->razorpay_payment_id,
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'capture-hotel-payment', $payload);
    }
}

