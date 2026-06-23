<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\HomeSection;
use App\Models\HomeTrustCard;
use App\Models\Hotel;
use App\Models\Offer;
use App\Models\Testimonial;
use App\Models\TrainRoute;
use App\Models\TravelPackage;
use App\Services\Travel\TravelCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function __construct(private TravelCatalogService $catalog) {}

    public function index(): JsonResponse
    {
        $offers = collect();
        if (Schema::hasTable('offers')) {
            $offers = Offer::query()->activeWindow()->orderByDesc('id')->limit(8)->get()
                ->map(fn ($o) => [
                    'id' => $o->id,
                    'title' => $o->title,
                    'description' => $o->description,
                    'redirect_url' => $o->redirect_url,
                ]);
        }

        $testimonials = collect();
        if (Schema::hasTable('testimonials')) {
            $testimonials = Testimonial::query()->where('is_active', true)->orderByDesc('id')->limit(8)->get()
                ->map(fn ($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'role' => $t->designation,
                    'content' => $t->review,
                    'rating' => $t->rating,
                ]);
        }

        $banners = collect();
        if (Schema::hasTable('banners')) {
            $banners = Banner::query()->where('is_active', true)->orderBy('sort_order')->get()
                ->map(fn ($b) => [
                    'id' => $b->id,
                    'title' => $b->title,
                    'subtitle' => $b->description,
                    'image_url' => $b->image ? url('/uploads/'.$b->image) : null,
                    'link_url' => $b->link,
                ]);
        }

        $trustCards = collect();
        if (Schema::hasTable('home_trust_cards')) {
            $trustCards = HomeTrustCard::query()->activeOrdered()->get()
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'title' => $c->title,
                    'description' => $c->description,
                    'icon' => $c->icon,
                ]);
        }

        $homeSections = collect();
        if (Schema::hasTable('home_sections')) {
            $homeSections = HomeSection::query()->activeOrdered()->get()
                ->map(fn ($s) => [
                    'id' => $s->id,
                    'key' => $s->partial_key,
                    'label' => $s->admin_label,
                    'settings' => $s->settings,
                ]);
        }

        return response()->json([
            'modules' => $this->catalog->modules,
            'featured_flights' => Flight::query()->where('is_active', true)->orderBy('departure_at')->limit(4)->get()
                ->map(fn ($f) => $this->catalog->mapListingRow('flights', $f)),
            'featured_hotels' => Hotel::query()->where('is_active', true)->orderByDesc('star_rating')->limit(4)->get()
                ->map(fn ($h) => $this->catalog->mapListingRow('hotels', $h)),
            'featured_packages' => TravelPackage::query()->where('is_published', true)->orderBy('name')->limit(4)->get()
                ->map(fn ($p) => $this->catalog->mapListingRow('packages', $p)),
            'top_destinations' => TravelPackage::query()->where('is_published', true)->orderByDesc('id')->pluck('destination')->unique()->take(6)->values(),
            'offers' => $offers,
            'testimonials' => $testimonials,
            'banners' => $banners,
            'trust_cards' => $trustCards,
            'home_sections' => $homeSections,
            'stats' => [
                'flights' => Flight::query()->where('is_active', true)->count(),
                'hotels' => Hotel::query()->where('is_active', true)->count(),
                'packages' => TravelPackage::query()->where('is_published', true)->count(),
                'bookings' => Booking::query()->count(),
                'buses' => BusRoute::query()->where('is_active', true)->count(),
                'trains' => TrainRoute::query()->where('is_active', true)->count(),
                'cabs' => CabService::query()->where('is_active', true)->count(),
            ],
        ]);
    }

    public function modules(): JsonResponse
    {
        return response()->json(['modules' => $this->catalog->modules]);
    }
}
