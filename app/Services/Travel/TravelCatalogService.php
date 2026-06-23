<?php

namespace App\Services\Travel;

use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\TrainRoute;
use App\Models\TravelAddon;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TravelCatalogService
{
    public const DB_MODULES = ['flights', 'hotels', 'packages', 'buses', 'trains', 'cabs'];

    public const ADDON_MODULES = ['visa', 'insurance'];

    /** @var array<string, array{title: string, icon: string, features: list<string>}> */
    public array $modules = [
        'flights' => ['title' => 'Flights', 'icon' => '✈', 'features' => ['One Way', 'Round Trip', 'Multi City', 'Seat Selection', 'Meal Selection']],
        'hotels' => ['title' => 'Hotels', 'icon' => '🏨', 'features' => ['City Search', 'Check-in/Check-out', 'Room Selection', 'Amenities', 'Reviews']],
        'packages' => ['title' => 'Holiday Packages', 'icon' => '🌍', 'features' => ['Domestic', 'International', 'Honeymoon', 'Family', 'Adventure']],
        'buses' => ['title' => 'Buses', 'icon' => '🚌', 'features' => ['Seat Selection', 'Boarding Points', 'Filters', 'Sleeper/Seater', 'Live Status']],
        'trains' => ['title' => 'Trains', 'icon' => '🚆', 'features' => ['Train Search', 'PNR Status', 'Seat Availability', 'Class Filter', 'Fare Details']],
        'cabs' => ['title' => 'Cabs', 'icon' => '🚖', 'features' => ['Airport Pickup', 'Outstation', 'Hourly Rental', 'Local Trips', 'Fare Estimation']],
        'visa' => ['title' => 'Visa Services', 'icon' => '🛂', 'features' => ['Country Wise Visa', 'Document Checklist', 'Application Support', 'Status Tracking', 'Consultation']],
        'insurance' => ['title' => 'Travel Insurance', 'icon' => '🛡', 'features' => ['Domestic Plan', 'International Plan', 'Medical Cover', 'Trip Cancellation', 'Claims Help']],
    ];

    public function isValidModule(string $slug): bool
    {
        return isset($this->modules[$slug]);
    }

    public function isBookableModule(string $slug): bool
    {
        return in_array($slug, self::DB_MODULES, true)
            || (in_array($slug, self::ADDON_MODULES, true) && Schema::hasTable('travel_addons'));
    }

    public function listingQuery(string $slug)
    {
        return match ($slug) {
            'flights' => Flight::query()->where('is_active', true)->orderBy('departure_at'),
            'hotels' => Hotel::query()->where('is_active', true)->orderBy('name'),
            'packages' => TravelPackage::query()->where('is_published', true)->orderBy('name'),
            'buses' => BusRoute::query()->where('is_active', true)->orderBy('departure_at'),
            'trains' => TrainRoute::query()->where('is_active', true)->orderBy('departure_at'),
            'cabs' => CabService::query()->where('is_active', true)->orderBy('service_type'),
            default => throw new \InvalidArgumentException($slug),
        };
    }

    public function applyListingFilters(Request $request, string $slug, $query): void
    {
        match ($slug) {
            'flights' => $this->filterFlights($request, $query),
            'hotels' => $this->filterHotels($request, $query),
            'packages' => $this->filterPackages($request, $query),
            'buses' => $this->filterBusesTrains($request, $query, 'from_city', 'to_city'),
            'trains' => $this->filterBusesTrains($request, $query, 'from_city', 'to_city'),
            'cabs' => $this->filterCabs($request, $query),
            default => null,
        };
    }

    public function applyListingSort(Request $request, string $slug, $query): void
    {
        if (! $request->filled('sort')) {
            return;
        }

        $priceColumn = match ($slug) {
            'flights' => 'price',
            'hotels' => 'price_per_night',
            'packages' => 'price',
            'buses', 'trains' => 'price',
            'cabs' => 'base_fare',
            default => null,
        };

        if ($priceColumn === null) {
            return;
        }

        match ($request->string('sort')->toString()) {
            'price_asc' => $query->reorder()->orderBy($priceColumn),
            'price_desc' => $query->reorder()->orderByDesc($priceColumn),
            default => null,
        };
    }

    public function resolveActiveItem(string $slug, string $item): Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon|null
    {
        $bySlug = $this->findActiveItemBySlug($slug, $item);
        if ($bySlug !== null) {
            return $bySlug;
        }
        if (ctype_digit((string) $item)) {
            return $this->findActiveItemById($slug, (int) $item);
        }

        return null;
    }

    public function resolveItemById(string $slug, int $id): Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon|null
    {
        if (in_array($slug, self::ADDON_MODULES, true) && Schema::hasTable('travel_addons')) {
            return TravelAddon::query()->where('category', $slug)->find($id);
        }

        return match ($slug) {
            'flights' => Flight::query()->find($id),
            'hotels' => Hotel::query()->find($id),
            'packages' => TravelPackage::query()->find($id),
            'buses' => BusRoute::query()->find($id),
            'trains' => TrainRoute::query()->find($id),
            'cabs' => CabService::query()->find($id),
            default => null,
        };
    }

    /**
     * @return array{id:int, title:string, subtitle:string, price:float, slug?:string}
     */
    public function mapListingRow(string $slug, Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon $model): array
    {
        if ($model instanceof TravelAddon) {
            return [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->name,
                'subtitle' => Str::limit((string) ($model->summary ?? ''), 140),
                'price' => (float) $model->price,
            ];
        }

        return match ($slug) {
            'flights' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->airline.' '.$model->flight_number,
                'subtitle' => $model->from_city.' → '.$model->to_city.' · '.$model->departure_at->format('d M Y, H:i')
                    .' · '.$model->cabin_class.' · '.$model->stops.' stop(s) · '
                    .abs((int) $model->departure_at->diffInMinutes($model->arrival_at)).' min',
                'price' => (float) $model->price,
            ],
            'hotels' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->name,
                'subtitle' => $model->city.($model->location ? ' · '.$model->location : '').' · '.$model->star_rating.'★',
                'price' => (float) $model->price_per_night,
            ],
            'packages' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->name,
                'subtitle' => $model->category.' · '.$model->destination.' · '.$model->duration_days.' days',
                'price' => (float) ($model->offer_price ?? $model->price),
            ],
            'buses' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->operator_name,
                'subtitle' => $model->from_city.' → '.$model->to_city.' · '.$model->departure_at->format('d M, H:i'),
                'price' => (float) $model->price,
            ],
            'trains' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->train_name.' ('.$model->train_number.')',
                'subtitle' => $model->from_city.' → '.$model->to_city.' · '.$model->departure_at->format('d M, H:i'),
                'price' => (float) $model->price,
            ],
            'cabs' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->service_type,
                'subtitle' => $model->from_location.($model->to_location ? ' → '.$model->to_location : ''),
                'price' => (float) $model->base_fare,
            ],
        };
    }

    /**
     * @return array{id:int, slug:string, title:string, description:string, price:float, meta?:string}
     */
    public function mapDetailRow(string $slug, Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon $model): array
    {
        if ($model instanceof TravelAddon) {
            return [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->name,
                'description' => $model->description ?? $model->summary ?? 'Travel add-on service.',
                'price' => (float) $model->price,
                'meta' => $model->summary ?? '',
            ];
        }

        return match ($slug) {
            'flights' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->airline.' '.$model->flight_number,
                'description' => 'Route '.$model->from_city.' to '.$model->to_city.'. Cabin '.$model->cabin_class.', '.$model->stops.' stop(s). Seats available: '.$model->seats_available.'.',
                'price' => (float) $model->price,
                'meta' => 'Departure: '.$model->departure_at->format('d M Y H:i').' · Arrival: '.$model->arrival_at->format('d M Y H:i'),
            ],
            'hotels' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->name,
                'description' => $model->description ?? 'Comfortable stay with selected amenities.',
                'price' => (float) $model->price_per_night,
                'meta' => $model->city.' · '.$model->star_rating.'★ · '.(is_array($model->amenities) && count($model->amenities) ? implode(', ', $model->amenities) : 'Amenities on request'),
            ],
            'packages' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->name,
                'description' => $model->details ?? $model->itinerary ?? 'Custom holiday package.',
                'price' => (float) ($model->offer_price ?? $model->price),
                'meta' => $model->category.' · '.$model->destination.' · '.$model->duration_days.' days',
            ],
            'buses' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->operator_name,
                'description' => 'Bus from '.$model->from_city.' to '.$model->to_city.'. Seats available: '.$model->seats_available.'.',
                'price' => (float) $model->price,
                'meta' => 'Departure: '.$model->departure_at->format('d M Y H:i').' · Arrival: '.$model->arrival_at->format('d M Y H:i'),
            ],
            'trains' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->train_name.' ('.$model->train_number.')',
                'description' => 'Train from '.$model->from_city.' to '.$model->to_city.'. Seats available: '.$model->seats_available.'.',
                'price' => (float) $model->price,
                'meta' => 'Departure: '.$model->departure_at->format('d M Y H:i').' · Arrival: '.$model->arrival_at->format('d M Y H:i'),
            ],
            'cabs' => [
                'id' => $model->id,
                'slug' => $model->slug,
                'title' => $model->service_type,
                'description' => 'Pickup '.$model->from_location.($model->to_location ? ', drop '.$model->to_location : '').'.',
                'price' => (float) $model->base_fare,
                'meta' => $model->per_km_rate !== null ? 'Per km: Rs '.number_format((float) $model->per_km_rate, 2) : 'Fixed base fare',
            ],
        };
    }

    public function unitPrice(string $slug, Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon $model): float
    {
        if ($model instanceof TravelAddon) {
            return (float) $model->price;
        }

        return match ($slug) {
            'flights', 'buses', 'trains' => (float) $model->price,
            'hotels' => (float) $model->price_per_night,
            'packages' => (float) ($model->offer_price ?? $model->price),
            'cabs' => (float) $model->base_fare,
        };
    }

    /**
     * @return array{ok: bool, pnr: string, message: string, train?: string, from?: string, to?: string, status?: string}
     */
    public function demoTrainPnrLookup(string $pnr): array
    {
        $pnr = strtoupper(preg_replace('/\s+/', '', $pnr));
        if (strlen($pnr) < 3 || strlen($pnr) > 12) {
            return [
                'ok' => false,
                'pnr' => $pnr,
                'message' => 'Enter a typical PNR (3–12 alphanumeric characters).',
            ];
        }

        return [
            'ok' => true,
            'pnr' => $pnr,
            'message' => 'Demo status — connect a rail provider API later for live PNR.',
            'train' => 'Sample Express (12'.substr(hash('crc32', $pnr), 0, 3).')',
            'from' => 'New Delhi (NDLS)',
            'to' => 'Mumbai Central (MMCT)',
            'status' => 'CNF / WL demo',
        ];
    }

    private function filterFlights(Request $request, $query): void
    {
        if ($request->filled('from')) {
            $query->where('from_city', 'like', '%'.$request->string('from').'%');
        }
        if ($request->filled('to')) {
            $query->where('to_city', 'like', '%'.$request->string('to').'%');
        }
        if ($request->filled('date')) {
            $query->whereDate('departure_at', $request->date('date'));
        }
        if ($request->filled('airline')) {
            $query->where('airline', 'like', '%'.$request->string('airline').'%');
        }
        if ($request->filled('cabin_class')) {
            $query->where('cabin_class', $request->string('cabin_class')->toString());
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }
        if ($request->filled('stops')) {
            $query->where('stops', '<=', (int) $request->input('stops'));
        }
    }

    private function filterHotels(Request $request, $query): void
    {
        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->string('city').'%');
        }
        if ($request->filled('q')) {
            $q = '%'.$request->string('q').'%';
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', $q)->orWhere('location', 'like', $q);
            });
        }
        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', (float) $request->input('max_price'));
        }
        if ($request->filled('min_stars')) {
            $query->where('star_rating', '>=', (int) $request->input('min_stars'));
        }
    }

    private function filterPackages(Request $request, $query): void
    {
        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%'.$request->string('destination').'%');
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%'.$request->string('category').'%');
        }
        if ($request->filled('q')) {
            $q = '%'.$request->string('q').'%';
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', $q)->orWhere('destination', 'like', $q);
            });
        }
    }

    private function filterBusesTrains(Request $request, $query, string $fromCol, string $toCol): void
    {
        if ($request->filled('from')) {
            $query->where($fromCol, 'like', '%'.$request->string('from').'%');
        }
        if ($request->filled('to')) {
            $query->where($toCol, 'like', '%'.$request->string('to').'%');
        }
        if ($request->filled('date')) {
            $query->whereDate('departure_at', $request->date('date'));
        }
    }

    private function filterCabs(Request $request, $query): void
    {
        if ($request->filled('q')) {
            $q = '%'.$request->string('q').'%';
            $query->where(function ($qry) use ($q) {
                $qry->where('service_type', 'like', $q)
                    ->orWhere('from_location', 'like', $q)
                    ->orWhere('to_location', 'like', $q);
            });
        }
    }

    private function findActiveItemBySlug(string $slug, string $itemSlug): Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon|null
    {
        if (in_array($slug, self::ADDON_MODULES, true) && Schema::hasTable('travel_addons')) {
            return TravelAddon::query()
                ->where('category', $slug)
                ->where('is_active', true)
                ->where('slug', $itemSlug)
                ->first();
        }

        return match ($slug) {
            'flights' => Flight::query()->where('is_active', true)->where('slug', $itemSlug)->first(),
            'hotels' => Hotel::query()->where('is_active', true)->where('slug', $itemSlug)->first(),
            'packages' => TravelPackage::query()->where('is_published', true)->where('slug', $itemSlug)->first(),
            'buses' => BusRoute::query()->where('is_active', true)->where('slug', $itemSlug)->first(),
            'trains' => TrainRoute::query()->where('is_active', true)->where('slug', $itemSlug)->first(),
            'cabs' => CabService::query()->where('is_active', true)->where('slug', $itemSlug)->first(),
            default => null,
        };
    }

    private function findActiveItemById(string $slug, int $id): Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon|null
    {
        if (in_array($slug, self::ADDON_MODULES, true) && Schema::hasTable('travel_addons')) {
            return TravelAddon::query()->where('category', $slug)->where('is_active', true)->find($id);
        }

        return match ($slug) {
            'flights' => Flight::query()->where('is_active', true)->find($id),
            'hotels' => Hotel::query()->where('is_active', true)->find($id),
            'packages' => TravelPackage::query()->where('is_published', true)->find($id),
            'buses' => BusRoute::query()->where('is_active', true)->find($id),
            'trains' => TrainRoute::query()->where('is_active', true)->find($id),
            'cabs' => CabService::query()->where('is_active', true)->find($id),
            default => null,
        };
    }
}
