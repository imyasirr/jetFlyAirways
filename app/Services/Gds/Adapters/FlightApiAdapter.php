<?php

namespace App\Services\Gds\Adapters;

use App\Models\ApiIntegration;
use App\Models\Booking;

class FlightApiAdapter extends AbstractHttpIntegrationAdapter
{
    public function supports(string $service): bool
    {
        return $service === 'flight_api';
    }

    public function onBookingCreated(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'flight',
            'flight' => [
                'item_id' => $booking->module_item_id,
                'trip_type' => $booking->trip_type,
                'return_date' => $booking->return_date?->format('Y-m-d'),
                'seat_preference' => $booking->seat_preference,
                'meal_preference' => $booking->meal_preference,
                'multi_city_notes' => $booking->multi_city_notes,
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'create-flight', $payload);
    }

    public function onPaymentCaptured(ApiIntegration $integration, Booking $booking): string
    {
        $payload = $this->bookingBasePayload($booking) + [
            'segment' => 'flight',
            'payment' => [
                'captured' => true,
                'payment_id' => $booking->razorpay_payment_id,
            ],
        ];

        return $this->postBookingEvent($integration, $booking, 'capture-flight-payment', $payload);
    }
}

