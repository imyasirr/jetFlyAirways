<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ApiIntegrationController extends Controller
{
    private const SERVICES = [
        'flight_api' => 'Flight Booking API',
        'hotel_api' => 'Hotel Booking API',
        'bus_api' => 'Bus Booking API',
        'cab_api' => 'Cab Booking API',
        'payment_gateway' => 'Payment Gateway API',
        'sms_api' => 'SMS API',
        'email_api' => 'Email API',
        'google_maps' => 'Google Maps API',
        'whatsapp_api' => 'WhatsApp API',
    ];

    public function index(): View
    {
        $rows = ApiIntegration::query()->get()->keyBy('service');
        $endpointTemplates = $this->endpointTemplates();
        $samplePayloads = $this->samplePayloads();

        $integrations = collect(self::SERVICES)->map(function (string $label, string $service) use ($rows) {
            $row = $rows->get($service);
            if (! $row) {
                $row = new ApiIntegration([
                    'service' => $service,
                    'display_name' => $label,
                    'is_active' => false,
                ]);
            }

            return $row;
        });

        return view('admin.integrations.index', compact('integrations', 'endpointTemplates', 'samplePayloads'));
    }

    public function update(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'integrations' => ['required', 'array'],
            'integrations.*.service' => ['required', 'string'],
            'integrations.*.display_name' => ['required', 'string', 'max:120'],
            'integrations.*.base_url' => ['nullable', 'string', 'max:500'],
            'integrations.*.api_key' => ['nullable', 'string', 'max:500'],
            'integrations.*.api_secret' => ['nullable', 'string', 'max:500'],
            'integrations.*.notes' => ['nullable', 'string', 'max:5000'],
            'integrations.*.is_active' => ['nullable', 'boolean'],
        ]);

        foreach ($payload['integrations'] as $item) {
            if (! array_key_exists($item['service'], self::SERVICES)) {
                continue;
            }

            ApiIntegration::query()->updateOrCreate(
                ['service' => $item['service']],
                [
                    'display_name' => $item['display_name'],
                    'base_url' => $item['base_url'] ?: null,
                    'api_key' => $item['api_key'] ?: null,
                    'api_secret' => $item['api_secret'] ?: null,
                    'notes' => $item['notes'] ?: null,
                    'is_active' => (bool) ($item['is_active'] ?? false),
                ]
            );
        }

        return redirect()->route('admin.integrations.index')->with('status', 'Integration settings updated.');
    }

    public function test(Request $request, ApiIntegration $integration): RedirectResponse
    {
        $status = 'Not configured';
        $baseUrl = trim((string) $integration->base_url);

        if ($baseUrl !== '') {
            try {
                $response = Http::timeout(6)->acceptJson()->get($baseUrl);
                $status = $response->successful()
                    ? 'Reachable (HTTP '.$response->status().')'
                    : 'Unreachable (HTTP '.$response->status().')';
            } catch (\Throwable $e) {
                $status = 'Connection failed: '.$e->getMessage();
            }
        }

        $integration->update([
            'last_checked_at' => Carbon::now(),
            'last_check_status' => $status,
        ]);

        return redirect()->route('admin.integrations.index')->with('status', $integration->display_name.' test finished: '.$status);
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function endpointTemplates(): array
    {
        return [
            'flight_api' => ['/bookings/create-flight', '/bookings/capture-flight-payment'],
            'hotel_api' => ['/bookings/create-hotel', '/bookings/capture-hotel-payment'],
            'bus_api' => ['/bookings/create-bus', '/bookings/capture-bus-payment'],
            'cab_api' => ['/bookings/create-cab', '/bookings/capture-cab-payment'],
            'payment_gateway' => ['/payments/create-order', '/payments/verify'],
            'sms_api' => ['/sms/send'],
            'email_api' => ['/email/send'],
            'google_maps' => ['/maps/geocode', '/maps/distance-matrix'],
            'whatsapp_api' => ['/messages/whatsapp/send'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function samplePayloads(): array
    {
        return [
            'flight_api' => [
                'booking_code' => 'JFA-FLI-AB12CD',
                'module_item_id' => 101,
                'travel_date' => '2026-06-12',
                'travellers_count' => 2,
                'total_amount' => 18450.00,
                'contact' => ['name' => 'Test User', 'email' => 'user@example.com', 'phone' => '+919999999999'],
            ],
            'hotel_api' => [
                'booking_code' => 'JFA-HOT-EF34GH',
                'module_item_id' => 202,
                'travel_date' => '2026-06-20',
                'rooms' => 2,
                'total_amount' => 12500.00,
            ],
            'bus_api' => [
                'booking_code' => 'JFA-BUS-IJ56KL',
                'module_item_id' => 303,
                'seats_requested' => 3,
                'travel_date' => '2026-06-10',
            ],
            'cab_api' => [
                'booking_code' => 'JFA-CAB-MN78OP',
                'module_item_id' => 404,
                'passengers' => 2,
                'travel_date' => '2026-06-18',
            ],
            'payment_gateway' => [
                'booking_code' => 'JFA-PAY-QR90ST',
                'amount' => 8400.00,
                'currency' => 'INR',
                'payment_id' => 'pay_example_123',
            ],
            'sms_api' => [
                'to' => '+919999999999',
                'message' => 'Your Jet Fly booking JFA-ABC123 is confirmed.',
            ],
            'email_api' => [
                'to' => 'user@example.com',
                'subject' => 'Booking Confirmation',
                'template' => 'booking_paid',
            ],
            'google_maps' => [
                'origin' => 'Delhi Airport',
                'destination' => 'Connaught Place',
                'mode' => 'driving',
            ],
            'whatsapp_api' => [
                'to' => '+919999999999',
                'message' => 'Hi, your Jet Fly booking JFA-ABC123 is confirmed.',
            ],
        ];
    }
}

