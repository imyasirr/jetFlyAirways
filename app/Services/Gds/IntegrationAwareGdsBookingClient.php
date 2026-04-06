<?php

namespace App\Services\Gds;

use App\Contracts\GdsBookingClient;
use App\Models\ApiIntegration;
use App\Models\Booking;
use App\Services\Gds\Adapters\BusApiAdapter;
use App\Services\Gds\Adapters\CabApiAdapter;
use App\Services\Gds\Adapters\FlightApiAdapter;
use App\Services\Gds\Adapters\HotelApiAdapter;
use App\Services\Gds\Adapters\ModuleIntegrationAdapter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class IntegrationAwareGdsBookingClient implements GdsBookingClient
{
    /** @var array<int, ModuleIntegrationAdapter> */
    private array $adapters;

    public function __construct(
        private readonly NullGdsBookingClient $fallback,
        FlightApiAdapter $flightApiAdapter,
        HotelApiAdapter $hotelApiAdapter,
        BusApiAdapter $busApiAdapter,
        CabApiAdapter $cabApiAdapter,
    ) {
        $this->adapters = [
            $flightApiAdapter,
            $hotelApiAdapter,
            $busApiAdapter,
            $cabApiAdapter,
        ];
    }

    public function searchFlights(array $criteria): array
    {
        $integration = $this->activeIntegration('flight_api');
        if (! $integration) {
            return $this->fallback->searchFlights($criteria);
        }

        return [
            'status' => 'configured',
            'provider' => $integration->display_name,
            'message' => 'Flight API integration is configured; wire supplier adapter to fetch live inventory.',
            'criteria' => $criteria,
        ];
    }

    public function searchHotels(array $criteria): array
    {
        $integration = $this->activeIntegration('hotel_api');
        if (! $integration) {
            return $this->fallback->searchHotels($criteria);
        }

        return [
            'status' => 'configured',
            'provider' => $integration->display_name,
            'message' => 'Hotel API integration is configured; wire supplier adapter to fetch live inventory.',
            'criteria' => $criteria,
        ];
    }

    public function recordBookingCreated(Booking $booking): void
    {
        $service = $this->serviceForModule($booking->module);
        $integration = $service ? $this->activeIntegration($service) : null;

        if (! $integration) {
            $this->fallback->recordBookingCreated($booking);
            $this->updateBookingProviderState($booking, 'internal_inventory', 'fallback: no active integration');
            return;
        }

        $adapter = $this->adapterForService($integration->service);
        if (! $adapter) {
            $this->updateBookingProviderState($booking, $integration->service, 'no adapter mapped for service');
            return;
        }

        try {
            $status = $adapter->onBookingCreated($integration, $booking);
            $this->updateBookingProviderState($booking, $integration->service, $status);
        } catch (\Throwable $e) {
            $this->updateBookingProviderState($booking, $integration->service, 'adapter error: '.$e->getMessage());
            Log::warning('[GDS integration router] booking create adapter error', [
                'booking_id' => $booking->id,
                'service' => $integration->service,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function recordPaymentCaptured(Booking $booking): void
    {
        $service = $this->serviceForModule($booking->module);
        $integration = $service ? $this->activeIntegration($service) : null;

        if (! $integration) {
            $this->fallback->recordPaymentCaptured($booking);
            $this->updateBookingProviderState($booking, $booking->provider_service ?: 'internal_inventory', 'payment captured (fallback)');
            return;
        }

        $adapter = $this->adapterForService($integration->service);
        if (! $adapter) {
            $this->updateBookingProviderState($booking, $integration->service, 'no adapter mapped for service');
            return;
        }

        try {
            $status = $adapter->onPaymentCaptured($integration, $booking);
            $this->updateBookingProviderState($booking, $integration->service, $status);
        } catch (\Throwable $e) {
            $this->updateBookingProviderState($booking, $integration->service, 'adapter error: '.$e->getMessage());
            Log::warning('[GDS integration router] payment capture adapter error', [
                'booking_id' => $booking->id,
                'service' => $integration->service,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function activeIntegration(string $service): ?ApiIntegration
    {
        if (! Schema::hasTable('api_integrations')) {
            return null;
        }

        return ApiIntegration::query()
            ->where('service', $service)
            ->where('is_active', true)
            ->first();
    }

    private function serviceForModule(string $module): ?string
    {
        return match ($module) {
            'flights' => 'flight_api',
            'hotels' => 'hotel_api',
            'buses' => 'bus_api',
            'cabs' => 'cab_api',
            default => null,
        };
    }

    private function adapterForService(string $service): ?ModuleIntegrationAdapter
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->supports($service)) {
                return $adapter;
            }
        }

        return null;
    }

    private function updateBookingProviderState(Booking $booking, string $service, string $status): void
    {
        if (! Schema::hasColumn('bookings', 'provider_service') || ! Schema::hasColumn('bookings', 'provider_sync_status')) {
            return;
        }

        $booking->forceFill([
            'provider_service' => $service,
            'provider_sync_status' => $status,
        ])->saveQuietly();
    }
}

