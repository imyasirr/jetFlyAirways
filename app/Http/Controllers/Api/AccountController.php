<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\SavedTraveller;
use App\Models\TrainRoute;
use App\Models\TravelAddon;
use App\Models\TravelPackage;
use App\Models\User;
use App\Models\WishlistItem;
use App\Support\PublicImageStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    private const WISHLIST_MODULES = ['flights', 'hotels', 'packages', 'buses', 'trains', 'cabs', 'visa', 'insurance'];

    public function bookings(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->claimOrphanBookings($user);

        $paginator = Booking::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($inner) use ($user) {
                        $inner->whereNull('user_id')
                            ->where('contact_email', $user->email);
                    });
            })
            ->orderByDesc('travel_date')
            ->paginate(12);

        $bookingController = app(BookingController::class);

        return response()->json([
            'bookings' => $paginator->getCollection()->map(fn ($b) => $bookingController->bookingPayload($b)),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function showBooking(Request $request, Booking $booking): JsonResponse
    {
        abort_unless($this->userOwnsBooking($request->user(), $booking), 403);

        return response()->json([
            'booking' => app(BookingController::class)->bookingPayload($booking),
        ]);
    }

    public function cancelBooking(Request $request, Booking $booking): JsonResponse
    {
        abort_unless($this->userOwnsBooking($request->user(), $booking), 403);

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'This booking is already cancelled.']);
        }

        if ($booking->travel_date->isPast()) {
            return response()->json(['message' => 'Past-travel bookings cannot be cancelled online.'], 422);
        }

        $booking->status = 'cancelled';
        $booking->payment_status = $booking->payment_status === 'paid' ? 'refund_initiated' : 'cancelled';
        $booking->save();

        return response()->json([
            'message' => 'Booking cancelled. Refund status has been updated.',
            'booking' => app(BookingController::class)->bookingPayload($booking),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'nationality' => ['nullable', 'string', 'max:80'],
        ]);

        $user->fill($validated);
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => app(AuthController::class)->userPayload($user->fresh()),
        ]);
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($request->boolean('clear_avatar')) {
            PublicImageStorage::deleteIfExists($user->avatar);
            $user->update(['avatar' => null]);

            return response()->json([
                'message' => 'Profile photo removed.',
                'user' => app(AuthController::class)->userPayload($user->fresh()),
            ]);
        }

        $request->validate([
            'avatar' => ['required_without:clear_avatar', 'image', 'mimes:jpeg,png,webp,gif', 'max:4096'],
            'clear_avatar' => ['sometimes', 'boolean'],
        ]);

        $path = PublicImageStorage::storeUpload($request->file('avatar'), 'avatars', $user->avatar);
        abort_if($path === null, 500, 'Profile photo upload failed.');

        $user->update(['avatar' => $path]);

        return response()->json([
            'message' => 'Profile photo updated.',
            'user' => app(AuthController::class)->userPayload($user->fresh()),
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function wishlist(Request $request): JsonResponse
    {
        $rows = collect();
        if (Schema::hasTable('wishlist_items')) {
            $items = WishlistItem::query()
                ->where('user_id', $request->user()->id)
                ->orderByDesc('id')
                ->get();

            $rows = $items->map(fn (WishlistItem $w) => $this->mapWishlistRow($w));
        }

        return response()->json(['items' => $rows]);
    }

    public function addWishlist(Request $request, string $module, int $id): JsonResponse
    {
        abort_unless(in_array($module, self::WISHLIST_MODULES, true), 404);
        abort_unless(Schema::hasTable('wishlist_items'), 503);

        WishlistItem::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'module' => $module,
            'module_item_id' => $id,
        ]);

        return response()->json(['message' => 'Saved to your wishlist.']);
    }

    public function removeWishlist(Request $request, string $module, int $id): JsonResponse
    {
        abort_unless(in_array($module, self::WISHLIST_MODULES, true), 404);

        WishlistItem::query()
            ->where('user_id', $request->user()->id)
            ->where('module', $module)
            ->where('module_item_id', $id)
            ->delete();

        return response()->json(['message' => 'Removed from wishlist.']);
    }

    public function savedTravellers(Request $request): JsonResponse
    {
        $travellers = $request->user()
            ->savedTravellers()
            ->orderBy('full_name')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'full_name' => $t->full_name,
                'email' => $t->email,
                'phone' => $t->phone,
            ]);

        return response()->json(['travellers' => $travellers]);
    }

    public function storeSavedTraveller(Request $request): JsonResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $traveller = $request->user()->savedTravellers()->create($data);

        return response()->json([
            'message' => 'Traveller saved.',
            'traveller' => [
                'id' => $traveller->id,
                'full_name' => $traveller->full_name,
                'email' => $traveller->email,
                'phone' => $traveller->phone,
            ],
        ], 201);
    }

    public function deleteSavedTraveller(Request $request, SavedTraveller $savedTraveller): JsonResponse
    {
        abort_unless($savedTraveller->user_id === $request->user()->id, 403);
        $savedTraveller->delete();

        return response()->json(['message' => 'Traveller removed.']);
    }

    public function announcements(Request $request): JsonResponse
    {
        if (! Schema::hasTable('announcements')) {
            return response()->json(['announcements' => []]);
        }

        $readIds = [];
        if (Schema::hasTable('announcement_reads')) {
            $readIds = AnnouncementRead::query()
                ->where('user_id', $request->user()->id)
                ->pluck('announcement_id')
                ->all();
        }

        $announcements = Announcement::query()
            ->published()
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'body' => $a->body,
                'is_read' => in_array($a->id, $readIds, true),
                'created_at' => $a->created_at?->toIso8601String(),
            ]);

        return response()->json(['announcements' => $announcements]);
    }

    public function markAnnouncementRead(Request $request, Announcement $announcement): JsonResponse
    {
        if (Schema::hasTable('announcement_reads')) {
            AnnouncementRead::query()->firstOrCreate([
                'user_id' => $request->user()->id,
                'announcement_id' => $announcement->id,
            ]);
        }

        return response()->json(['message' => 'Marked as read.']);
    }

    public function refunds(Request $request): JsonResponse
    {
        $bookings = $request->user()
            ->bookings()
            ->whereIn('payment_status', ['refund_initiated', 'refunded', 'cancelled'])
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn ($b) => app(BookingController::class)->bookingPayload($b));

        return response()->json(['refunds' => $bookings]);
    }

    /**
     * @return array{module: string, module_item_id: int, title: string, slug: string|null}
     */
    private function mapWishlistRow(WishlistItem $w): array
    {
        $model = match ($w->module) {
            'flights' => Flight::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'hotels' => Hotel::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'packages' => TravelPackage::query()->whereKey($w->module_item_id)->where('is_published', true)->first(),
            'buses' => BusRoute::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'trains' => TrainRoute::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'cabs' => CabService::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'visa', 'insurance' => TravelAddon::query()
                ->where('category', $w->module)
                ->whereKey($w->module_item_id)
                ->where('is_active', true)
                ->first(),
            default => null,
        };

        $title = ucfirst($w->module).' #'.$w->module_item_id;
        $slug = null;

        if ($model !== null) {
            $slug = $model->slug ?? null;
            $title = match ($w->module) {
                'flights' => $model->airline.' '.$model->flight_number,
                'hotels' => $model->name,
                'packages' => $model->name,
                'buses' => $model->operator_name.' · '.$model->from_city.' → '.$model->to_city,
                'trains' => $model->train_number.' · '.$model->from_city.' → '.$model->to_city,
                'cabs' => $model->service_type.' · '.$model->from_location,
                'visa', 'insurance' => $model->name,
                default => $title,
            };
        }

        return [
            'module' => $w->module,
            'module_item_id' => (int) $w->module_item_id,
            'title' => $title,
            'slug' => $slug,
        ];
    }

    private function claimOrphanBookings(User $user): void
    {
        Booking::query()
            ->whereNull('user_id')
            ->where('contact_email', $user->email)
            ->update(['user_id' => $user->id]);
    }

    private function userOwnsBooking(User $user, Booking $booking): bool
    {
        if ($booking->user_id === $user->id) {
            return true;
        }

        return $booking->user_id === null
            && strcasecmp((string) $booking->contact_email, $user->email) === 0;
    }
}
