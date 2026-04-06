<?php

namespace App\Services\Gds\Adapters;

use App\Models\ApiIntegration;
use App\Models\Booking;

class CabApiAdapter extends AbstractHttpIntegrationAdapter
{
    public function supports(string $service): bool
    {
        return $service === 'cab_api';
    }

    public function onBookingCreated(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'cab',
            'cab' => [
                'item_id' => $booking->module_item_id,
                'passengers' => $booking->travellers_count,
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'create-cab', $payload);
    }

    public function onPaymentCaptured(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'cab',
            'payment' => [
                'captured' => true,
                'payment_id' => $booking->razorpay_payment_id,
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'capture-cab-payment', $payload);
    }
}

