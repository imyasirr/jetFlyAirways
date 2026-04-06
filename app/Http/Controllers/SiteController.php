<?php

namespace App\Http\Controllers;

use App\Contracts\GdsBookingClient;
use App\Mail\BookingPlacedMail;
use App\Models\Banner;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\HomeSection;
use App\Models\Hotel;
use App\Models\Offer;
use App\Models\SavedTraveller;
use App\Models\Testimonial;
use App\Models\WishlistItem;
use App\Models\TrainRoute;
use App\Models\TravelAddon;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiteController extends Controller
{
    /** Modules backed by database tables (admin CRUD). */
    private const DB_MODULES = ['flights', 'hotels', 'packages', 'buses', 'trains', 'cabs'];

    /** Visa & insurance plans (travel_addons). */
    private const ADDON_MODULES = ['visa', 'insurance'];

    private array $modules = [
        'flights' => ['title' => 'Flights', 'icon' => '✈', 'features' => ['One Way', 'Round Trip', 'Multi City', 'Seat Selection', 'Meal Selection']],
        'hotels' => ['title' => 'Hotels', 'icon' => '🏨', 'features' => ['City Search', 'Check-in/Check-out', 'Room Selection', 'Amenities', 'Reviews']],
        'packages' => ['title' => 'Holiday Packages', 'icon' => '🌍', 'features' => ['Domestic', 'International', 'Honeymoon', 'Family', 'Adventure']],
        'buses' => ['title' => 'Buses', 'icon' => '🚌', 'features' => ['Seat Selection', 'Boarding Points', 'Filters', 'Sleeper/Seater', 'Live Status']],
        'trains' => ['title' => 'Trains', 'icon' => '🚆', 'features' => ['Train Search', 'PNR Status', 'Seat Availability', 'Class Filter', 'Fare Details']],
        'cabs' => ['title' => 'Cabs', 'icon' => '🚖', 'features' => ['Airport Pickup', 'Outstation', 'Hourly Rental', 'Local Trips', 'Fare Estimation']],
        'visa' => ['title' => 'Visa Services', 'icon' => '🛂', 'features' => ['Country Wise Visa', 'Document Checklist', 'Application Support', 'Status Tracking', 'Consultation']],
        'insurance' => ['title' => 'Travel Insurance', 'icon' => '🛡', 'features' => ['Domestic Plan', 'International Plan', 'Medical Cover', 'Trip Cancellation', 'Claims Help']],
    ];

    public function home()
    {
        return view('home', $this->homePageData());
    }

    /** Enterprise landing — same live data as home, distinct layout. */
    public function welcome()
    {
        return view('welcome', $this->homePageData());
    }

    public function referEarn(): View
    {
        $shareUrl = null;
        if (auth()->check() && filled(auth()->user()->referral_code)) {
            $shareUrl = route('register', ['ref' => auth()->user()->referral_code]);
        }

        return view('refer-earn', [
            'shareUrl' => $shareUrl,
            'referralsCount' => auth()->check() ? auth()->user()->referredUsers()->count() : 0,
        ]);
    }

    public function currencyConverter(): View
    {
        return view('currency-converter');
    }

    /**
     * @return array<string, mixed>
     */
    private function homePageData(): array
    {
        $offers = collect();
        if (Schema::hasTable('offers')) {
            $offers = Offer::query()->activeWindow()->orderByDesc('id')->limit(8)->get();
        }

        $testimonials = collect();
        if (Schema::hasTable('testimonials')) {
            $testimonials = Testimonial::query()->where('is_active', true)->orderByDesc('id')->limit(8)->get();
        }

        $banners = collect();
        if (Schema::hasTable('banners')) {
            $banners = Banner::query()->where('is_active', true)->orderBy('sort_order')->get();
        }

        $homeSections = collect();
        if (Schema::hasTable('home_sections')) {
            $homeSections = HomeSection::query()->activeOrdered()->get();
        }

        return [
            'modules' => $this->modules,
            'featuredFlights' => Flight::query()->where('is_active', true)->orderBy('departure_at')->limit(4)->get(),
            'featuredHotels' => Hotel::query()->where('is_active', true)->orderByDesc('star_rating')->limit(4)->get(),
            'featuredPackages' => TravelPackage::query()->where('is_published', true)->orderBy('name')->limit(4)->get(),
            'topDestinations' => TravelPackage::query()->where('is_published', true)->orderByDesc('id')->pluck('destination')->unique()->take(6)->values(),
            'offers' => $offers,
            'testimonials' => $testimonials,
            'banners' => $banners,
            'homeSections' => $homeSections,
            'stats' => [
                'flights' => Flight::query()->where('is_active', true)->count(),
                'hotels' => Hotel::query()->where('is_active', true)->count(),
                'packages' => TravelPackage::query()->where('is_published', true)->count(),
                'bookings' => Booking::query()->count(),
                'buses' => BusRoute::query()->where('is_active', true)->count(),
                'trains' => TrainRoute::query()->where('is_active', true)->count(),
                'cabs' => CabService::query()->where('is_active', true)->count(),
            ],
        ];
    }

    public function bookingThanks(Booking $booking): View
    {
        return view('booking-thanks', [
            'booking' => $booking,
            'ticketPdfUrl' => $booking->payment_status === 'paid'
                ? URL::temporarySignedRoute('bookings.ticket.pdf', now()->addHours(48), ['booking' => $booking->id])
                : null,
        ]);
    }

    public function module(Request $request, string $slug)
    {
        abort_unless(isset($this->modules[$slug]), 404);

        if (in_array($slug, self::ADDON_MODULES, true)) {
            if (! Schema::hasTable('travel_addons')) {
                return view('module', [
                    'slug' => $slug,
                    'module' => $this->modules[$slug],
                    'items' => null,
                    'staticModule' => true,
                    'addonCatalog' => false,
                ]);
            }

            $paginator = TravelAddon::query()
                ->where('category', $slug)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->paginate(12)
                ->withQueryString();
            $items = $paginator->through(function (TravelAddon $m) {
                return [
                    'id' => $m->id,
                    'title' => $m->name,
                    'subtitle' => Str::limit((string) ($m->summary ?? ''), 140),
                    'price' => (float) $m->price,
                ];
            });

            return view('module', [
                'slug' => $slug,
                'module' => $this->modules[$slug],
                'items' => $items,
                'staticModule' => false,
                'addonCatalog' => true,
            ]);
        }

        if (! in_array($slug, self::DB_MODULES, true)) {
            return view('module', [
                'slug' => $slug,
                'module' => $this->modules[$slug],
                'items' => null,
                'staticModule' => true,
                'addonCatalog' => false,
            ]);
        }

        $query = $this->listingQuery($slug);
        $this->applyListingFilters($request, $slug, $query);
        $paginator = $query->paginate(12)->withQueryString();
        $items = $paginator->through(fn ($model) => $this->mapListingRow($slug, $model));

        return view('module', [
            'slug' => $slug,
            'module' => $this->modules[$slug],
            'items' => $items,
            'staticModule' => false,
            'addonCatalog' => false,
        ]);
    }

    public function moduleDetail(string $slug, int $id)
    {
        abort_unless(isset($this->modules[$slug]), 404);

        if (! in_array($slug, self::DB_MODULES, true) && ! in_array($slug, self::ADDON_MODULES, true)) {
            abort(404);
        }

        $model = $this->findActiveItem($slug, $id);
        abort_if($model === null, 404);

        $inWishlist = false;
        if (auth()->check() && Schema::hasTable('wishlist_items')) {
            $inWishlist = WishlistItem::query()
                ->where('user_id', auth()->id())
                ->where('module', $slug)
                ->where('module_item_id', $id)
                ->exists();
        }

        return view('module-detail', [
            'slug' => $slug,
            'module' => $this->modules[$slug],
            'item' => $this->mapDetailRow($slug, $model),
            'inWishlist' => $inWishlist,
        ]);
    }

    public function bookingForm(string $slug, int $id)
    {
        abort_unless(isset($this->modules[$slug]), 404);
        abort_unless($this->isBookableModule($slug), 404);

        $model = $this->findActiveItem($slug, $id);
        abort_if($model === null, 404);

        return view('booking', [
            'slug' => $slug,
            'id' => $id,
            'module' => $this->modules[$slug],
            'item' => $this->mapDetailRow($slug, $model),
            'savedTravellers' => auth()->check()
                ? SavedTraveller::query()->where('user_id', auth()->id())->orderBy('full_name')->get()
                : collect(),
        ]);
    }

    public function bookingSubmit(Request $request, string $slug, int $id, GdsBookingClient $gds)
    {
        abort_unless(isset($this->modules[$slug]), 404);
        abort_unless($this->isBookableModule($slug), 404);

        $model = $this->findActiveItem($slug, $id);
        abort_if($model === null, 404);

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'travellers' => ['required', 'integer', 'min:1', 'max:20'],
            'travel_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'save_traveller' => ['nullable', 'boolean'],
        ];

        if ($slug === 'flights') {
            $rules['trip_type'] = ['required', 'in:one_way,round_trip,multi_city'];
            $rules['return_date'] = ['nullable', 'date', 'after_or_equal:travel_date'];
            $rules['seat_preference'] = ['nullable', 'string', 'max:80'];
            $rules['meal_preference'] = ['nullable', 'string', 'max:120'];
            $rules['multi_city_notes'] = ['nullable', 'string', 'max:2000'];
        }

        $validated = $request->validate($rules);

        if ($slug === 'flights' && ($validated['trip_type'] ?? '') === 'round_trip') {
            $validated = array_merge($validated, $request->validate([
                'return_date' => ['required', 'date', 'after_or_equal:travel_date'],
            ]));
        }

        $unitPrice = $this->unitPrice($slug, $model);
        $priceMultiplier = 1.0;
        if ($slug === 'flights') {
            $priceMultiplier = match ($validated['trip_type'] ?? 'one_way') {
                'round_trip' => 2.0,
                'multi_city' => 1.5,
                default => 1.0,
            };
        }
        $total = round($unitPrice * (int) $validated['travellers'] * $priceMultiplier, 2);

        do {
            $bookingCode = 'JFA-'.strtoupper(Str::limit($slug, 3, '')).'-'.strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $bookingCode)->exists());

        $bookingAttrs = [
            'user_id' => auth()->id(),
            'booking_code' => $bookingCode,
            'module' => $slug,
            'module_item_id' => $id,
            'travel_date' => $validated['travel_date'],
            'travellers_count' => (int) $validated['travellers'],
            'total_amount' => $total,
            'status' => 'confirmed',
            'payment_status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'contact_name' => $validated['name'],
            'contact_email' => $validated['email'],
            'contact_phone' => $validated['phone'],
        ];

        if ($slug === 'flights') {
            $bookingAttrs['trip_type'] = $validated['trip_type'];
            $bookingAttrs['return_date'] = $validated['return_date'] ?? null;
            $bookingAttrs['seat_preference'] = $validated['seat_preference'] ?? null;
            $bookingAttrs['meal_preference'] = $validated['meal_preference'] ?? null;
            $bookingAttrs['multi_city_notes'] = $validated['multi_city_notes'] ?? null;
        }

        $booking = Booking::create($bookingAttrs);

        if (auth()->check() && (bool) ($validated['save_traveller'] ?? false)) {
            SavedTraveller::query()->updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'email' => $validated['email'],
                ],
                [
                    'full_name' => $validated['name'],
                    'phone' => $validated['phone'],
                ]
            );
        }

        $paymentCheckoutUrl = null;
        if (config('services.razorpay.key') && config('services.razorpay.secret')) {
            $paymentCheckoutUrl = URL::temporarySignedRoute('payments.checkout', now()->addDays(7), ['booking' => $booking->id]);
        }

        try {
            $gds->recordBookingCreated($booking);
        } catch (\Throwable) {
            // GDS stub or future integration must not block checkout.
        }

        if (filter_var($validated['email'], FILTER_VALIDATE_EMAIL)) {
            try {
                $itemTitle = $this->mapDetailRow($slug, $model)['title'];
                Mail::to($validated['email'])->send(new BookingPlacedMail($booking, $itemTitle, $paymentCheckoutUrl));
            } catch (\Throwable) {
                // SMTP may be unset in local dev.
            }
        }

        return view('booking-confirmation', [
            'slug' => $slug,
            'id' => $id,
            'module' => $this->modules[$slug],
            'item' => $this->mapDetailRow($slug, $model),
            'data' => $validated,
            'bookingCode' => $booking->booking_code,
            'totalAmount' => $total,
            'booking' => $booking,
            'paymentCheckoutUrl' => $paymentCheckoutUrl,
        ]);
    }

    private function listingQuery(string $slug)
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

    private function applyListingFilters(Request $request, string $slug, $query): void
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

    private function isBookableModule(string $slug): bool
    {
        return in_array($slug, self::DB_MODULES, true)
            || (in_array($slug, self::ADDON_MODULES, true) && Schema::hasTable('travel_addons'));
    }

    private function findActiveItem(string $slug, int $id): Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon|null
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

    /**
     * @return array{id:int, title:string, subtitle:string, price:float}
     */
    private function mapListingRow(string $slug, Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService $model): array
    {
        return match ($slug) {
            'flights' => [
                'id' => $model->id,
                'title' => $model->airline.' '.$model->flight_number,
                'subtitle' => $model->from_city.' → '.$model->to_city.' · '.$model->departure_at->format('d M Y, H:i'),
                'price' => (float) $model->price,
            ],
            'hotels' => [
                'id' => $model->id,
                'title' => $model->name,
                'subtitle' => $model->city.($model->location ? ' · '.$model->location : '').' · '.$model->star_rating.'★',
                'price' => (float) $model->price_per_night,
            ],
            'packages' => [
                'id' => $model->id,
                'title' => $model->name,
                'subtitle' => $model->category.' · '.$model->destination.' · '.$model->duration_days.' days',
                'price' => (float) ($model->offer_price ?? $model->price),
            ],
            'buses' => [
                'id' => $model->id,
                'title' => $model->operator_name,
                'subtitle' => $model->from_city.' → '.$model->to_city.' · '.$model->departure_at->format('d M, H:i'),
                'price' => (float) $model->price,
            ],
            'trains' => [
                'id' => $model->id,
                'title' => $model->train_name.' ('.$model->train_number.')',
                'subtitle' => $model->from_city.' → '.$model->to_city.' · '.$model->departure_at->format('d M, H:i'),
                'price' => (float) $model->price,
            ],
            'cabs' => [
                'id' => $model->id,
                'title' => $model->service_type,
                'subtitle' => $model->from_location.($model->to_location ? ' → '.$model->to_location : ''),
                'price' => (float) $model->base_fare,
            ],
        };
    }

    /**
     * @return array{id:int, title:string, description:string, price:float, meta?:string}
     */
    private function mapDetailRow(string $slug, Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon $model): array
    {
        if ($model instanceof TravelAddon) {
            return [
                'id' => $model->id,
                'title' => $model->name,
                'description' => $model->description ?? $model->summary ?? __('jetfly.addon_default_description'),
                'price' => (float) $model->price,
                'meta' => $model->summary ?? '',
            ];
        }

        return match ($slug) {
            'flights' => [
                'id' => $model->id,
                'title' => $model->airline.' '.$model->flight_number,
                'description' => 'Route '.$model->from_city.' to '.$model->to_city.'. Cabin '.$model->cabin_class.', '.$model->stops.' stop(s). Seats available: '.$model->seats_available.'.',
                'price' => (float) $model->price,
                'meta' => 'Departure: '.$model->departure_at->format('d M Y H:i').' · Arrival: '.$model->arrival_at->format('d M Y H:i'),
            ],
            'hotels' => [
                'id' => $model->id,
                'title' => $model->name,
                'description' => $model->description ?? 'Comfortable stay with selected amenities.',
                'price' => (float) $model->price_per_night,
                'meta' => $model->city.' · '.$model->star_rating.'★ · '.(is_array($model->amenities) && count($model->amenities) ? implode(', ', $model->amenities) : 'Amenities on request'),
            ],
            'packages' => [
                'id' => $model->id,
                'title' => $model->name,
                'description' => $model->details ?? $model->itinerary ?? 'Custom holiday package.',
                'price' => (float) ($model->offer_price ?? $model->price),
                'meta' => $model->category.' · '.$model->destination.' · '.$model->duration_days.' days',
            ],
            'buses' => [
                'id' => $model->id,
                'title' => $model->operator_name,
                'description' => 'Bus from '.$model->from_city.' to '.$model->to_city.'. Seats available: '.$model->seats_available.'.',
                'price' => (float) $model->price,
                'meta' => 'Departure: '.$model->departure_at->format('d M Y H:i').' · Arrival: '.$model->arrival_at->format('d M Y H:i'),
            ],
            'trains' => [
                'id' => $model->id,
                'title' => $model->train_name.' ('.$model->train_number.')',
                'description' => 'Train from '.$model->from_city.' to '.$model->to_city.'. Seats available: '.$model->seats_available.'.',
                'price' => (float) $model->price,
                'meta' => 'Departure: '.$model->departure_at->format('d M Y H:i').' · Arrival: '.$model->arrival_at->format('d M Y H:i'),
            ],
            'cabs' => [
                'id' => $model->id,
                'title' => $model->service_type,
                'description' => 'Pickup '.$model->from_location.($model->to_location ? ', drop '.$model->to_location : '').'.',
                'price' => (float) $model->base_fare,
                'meta' => $model->per_km_rate !== null ? 'Per km: Rs '.number_format((float) $model->per_km_rate, 2) : 'Fixed base fare',
            ],
        };
    }

    private function unitPrice(string $slug, Flight|Hotel|TravelPackage|BusRoute|TrainRoute|CabService|TravelAddon $model): float
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
}
