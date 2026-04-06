<?php

namespace App\Services\Gds\Adapters;

use App\Models\ApiIntegration;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;

abstract class AbstractHttpIntegrationAdapter implements ModuleIntegrationAdapter
{
    protected function postBookingEvent(ApiIntegration $integration, Booking $booking, string $event, array $payload): string
    {
        $baseUrl = trim((string) $integration->base_url);
        if ($baseUrl === '') {
            return 'adapter_ready: missing base_url';
        }

        $endpoint = rtrim($baseUrl, '/').'/bookings/'.ltrim($event, '/');

        $headers = [
            'Accept' => 'application/json',
            'X-Provider-Service' => $integration->service,
        ];
        if (! empty($integration->api_key)) {
            $headers['Authorization'] = 'Bearer '.$integration->api_key;
        }
        if (! empty($integration->api_secret)) {
            $headers['X-Api-Secret'] = $integration->api_secret;
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders($headers)
                ->post($endpoint, $payload);

            return $response->successful()
                ? 'synced: '.$event.' (HTTP '.$response->status().')'
                : 'provider_error: '.$event.' (HTTP '.$response->status().')';
        } catch (\Throwable $e) {
            return 'connection_error: '.$event.' ('.$e->getMessage().')';
        }
    }

    protected function bookingBasePayload(Booking $booking): array
    {
        return [
            'booking_id' => $booking->id,
            'booking_code' => $booking->booking_code,
            'module' => $booking->module,
            'module_item_id' => $booking->module_item_id,
            'travel_date' => $booking->travel_date?->format('Y-m-d'),
            'travellers_count' => $booking->travellers_count,
            'total_amount' => (float) $booking->total_amount,
            'currency' => 'INR',
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'contact' => [
                'name' => $booking->contact_name,
                'email' => $booking->contact_email,
                'phone' => $booking->contact_phone,
            ],
            'notes' => $booking->notes,
        ];
    }
}

