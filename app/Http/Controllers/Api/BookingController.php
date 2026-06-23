<?php

namespace App\Http\Controllers\Api;

use App\Contracts\GdsBookingClient;
use App\Http\Controllers\Api\Concerns\ResolvesApiUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingPlacedMail;
use App\Models\Booking;
use App\Models\SavedTraveller;
use App\Services\Bookings\CouponDiscountCalculator;
use App\Services\Travel\TravelCatalogService;
use App\Support\PaymentGatewaySettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    use ResolvesApiUser;

    public function __construct(private TravelCatalogService $catalog) {}

    public function store(StoreBookingRequest $request, string $module, string $item, GdsBookingClient $gds, CouponDiscountCalculator $coupons): JsonResponse
    {
        abort_unless($this->catalog->isValidModule($module), 404);
        abort_unless($this->catalog->isBookableModule($module), 404);

        $model = $this->catalog->resolveActiveItem($module, $item);
        abort_if($model === null, 404);

        $validated = $request->validated();

        $unitPrice = $this->catalog->unitPrice($module, $model);
        $priceMultiplier = 1.0;
        if ($module === 'flights') {
            $priceMultiplier = match ($validated['trip_type'] ?? 'one_way') {
                'round_trip' => 2.0,
                'multi_city' => 1.5,
                default => 1.0,
            };
        }
        $subtotal = round($unitPrice * (int) $validated['travellers'] * $priceMultiplier, 2);

        $calc = $coupons->apply($validated['coupon_code'] ?? null, $subtotal);
        if ($calc['error'] !== null) {
            return response()->json(['message' => $calc['error'], 'errors' => ['coupon_code' => [$calc['error']]]], 422);
        }

        $total = $calc['total'];

        do {
            $bookingCode = 'JFA-'.strtoupper(Str::limit($module, 3, '')).'-'.strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $bookingCode)->exists());

        $bookingAttrs = [
            'user_id' => $this->apiUser($request)?->id,
            'booking_code' => $bookingCode,
            'module' => $module,
            'module_item_id' => $model->id,
            'travel_date' => $validated['travel_date'],
            'travellers_count' => (int) $validated['travellers'],
            'subtotal_amount' => $calc['subtotal'],
            'discount_amount' => $calc['discount'],
            'coupon_code' => $calc['coupon']?->code,
            'total_amount' => $total,
            'status' => 'confirmed',
            'payment_status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'contact_name' => $validated['name'],
            'contact_email' => $validated['email'],
            'contact_phone' => $validated['phone'],
        ];

        if ($module === 'flights') {
            $bookingAttrs['trip_type'] = $validated['trip_type'];
            $bookingAttrs['return_date'] = $validated['return_date'] ?? null;
            $bookingAttrs['seat_preference'] = $validated['seat_preference'] ?? null;
            $bookingAttrs['meal_preference'] = $validated['meal_preference'] ?? null;
            $bookingAttrs['multi_city_notes'] = $validated['multi_city_notes'] ?? null;
        }

        $booking = Booking::create($bookingAttrs);

        if ($this->apiUser($request) && (bool) ($validated['save_traveller'] ?? false)) {
            SavedTraveller::query()->updateOrCreate(
                [
                    'user_id' => $this->apiUser($request)->id,
                    'email' => $validated['email'],
                ],
                [
                    'full_name' => $validated['name'],
                    'phone' => $validated['phone'],
                ]
            );
        }

        try {
            $gds->recordBookingCreated($booking);
        } catch (\Throwable) {
            //
        }

        if (filter_var($validated['email'], FILTER_VALIDATE_EMAIL)) {
            try {
                $itemTitle = $this->catalog->mapDetailRow($module, $model)['title'];
                Mail::to($validated['email'])->send(new BookingPlacedMail($booking, $itemTitle));
            } catch (\Throwable) {
                //
            }
        }

        return response()->json([
            'message' => 'Booking created successfully.',
            'booking' => $this->bookingPayload($booking),
            'payment_available' => PaymentGatewaySettings::isConfigured(),
        ], 201);
    }

    public function validateCoupon(Request $request, CouponDiscountCalculator $coupons): JsonResponse
    {
        $data = $request->validate([
            'coupon_code' => ['required', 'string', 'max:40'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $calc = $coupons->apply($data['coupon_code'], (float) $data['subtotal']);
        if ($calc['error'] !== null) {
            return response()->json(['message' => $calc['error'], 'valid' => false], 422);
        }

        return response()->json([
            'valid' => true,
            'subtotal' => $calc['subtotal'],
            'discount' => $calc['discount'],
            'total' => $calc['total'],
            'coupon_code' => $calc['coupon']?->code,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function bookingPayload(Booking $booking): array
    {
        $itemTitle = null;
        try {
            $model = $this->catalog->resolveItemById($booking->module, $booking->module_item_id);
            if ($model !== null) {
                $itemTitle = $this->catalog->mapListingRow($booking->module, $model)['title'];
            }
        } catch (\Throwable) {
            //
        }

        return [
            'id' => $booking->id,
            'booking_code' => $booking->booking_code,
            'module' => $booking->module,
            'module_item_id' => $booking->module_item_id,
            'item_title' => $itemTitle,
            'travel_date' => $booking->travel_date->format('Y-m-d'),
            'return_date' => $booking->return_date?->format('Y-m-d'),
            'travellers_count' => $booking->travellers_count,
            'subtotal_amount' => (float) $booking->subtotal_amount,
            'discount_amount' => (float) $booking->discount_amount,
            'total_amount' => (float) $booking->total_amount,
            'coupon_code' => $booking->coupon_code,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'contact_name' => $booking->contact_name,
            'contact_email' => $booking->contact_email,
            'contact_phone' => $booking->contact_phone,
            'trip_type' => $booking->trip_type,
            'seat_preference' => $booking->seat_preference,
            'meal_preference' => $booking->meal_preference,
            'notes' => $booking->notes,
            'razorpay_order_id' => $booking->razorpay_order_id,
            'created_at' => $booking->created_at?->toIso8601String(),
        ];
    }
}
