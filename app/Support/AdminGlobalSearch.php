<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\ContactInquiry;
use App\Models\Coupon;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

final class AdminGlobalSearch
{
    private const MIN_LENGTH = 2;

    private const MAX_PER_GROUP = 5;

    /**
     * @return array{query: string, groups: list<array{label: string, items: list<array<string, mixed>>}>}
     */
    public function search(string $query): array
    {
        $query = trim($query);
        if (mb_strlen($query) < self::MIN_LENGTH) {
            return ['query' => $query, 'groups' => []];
        }

        $groups = array_values(array_filter([
            $this->group('Navigation', $this->searchNavigation($query)),
            $this->group('Bookings', $this->searchBookings($query)),
            $this->group('Flights', $this->searchFlights($query)),
            $this->group('Hotels', $this->searchHotels($query)),
            $this->group('Packages', $this->searchPackages($query)),
            $this->group('CMS pages', $this->searchPages($query)),
            $this->group('Blog', $this->searchBlogs($query)),
            $this->group('Customers', $this->searchUsers($query)),
            $this->group('Coupons', $this->searchCoupons($query)),
            $this->group('Testimonials', $this->searchTestimonials($query)),
            $this->group('Contact inquiries', $this->searchContactInquiries($query)),
        ], fn (?array $group) => $group !== null));

        return [
            'query' => $query,
            'groups' => $groups,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return array{label: string, items: list<array<string, mixed>>}|null
     */
    private function group(string $label, array $items): ?array
    {
        if ($items === []) {
            return null;
        }

        return [
            'label' => $label,
            'items' => $items,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchNavigation(string $query): array
    {
        $needle = mb_strtolower($query);
        $items = [];

        foreach (AdminNavigation::groups() as $group) {
            foreach ($group['items'] as $item) {
                $haystack = mb_strtolower($group['label'].' '.$item['label']);
                if (! str_contains($haystack, $needle)) {
                    continue;
                }

                if (! Route::has($item['route'])) {
                    continue;
                }

                $items[] = [
                    'title' => $item['label'],
                    'subtitle' => $group['label'].' · Admin section',
                    'url' => route($item['route']),
                    'icon' => $group['icon'],
                ];
            }
        }

        return array_slice($items, 0, self::MAX_PER_GROUP);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchBookings(string $query): array
    {
        if (! Schema::hasTable('bookings')) {
            return [];
        }

        return Booking::query()
            ->where(function ($builder) use ($query) {
                $builder->where('booking_code', 'like', $this->like($query))
                    ->orWhere('contact_name', 'like', $this->like($query))
                    ->orWhere('contact_email', 'like', $this->like($query))
                    ->orWhere('contact_phone', 'like', $this->like($query))
                    ->orWhere('module', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (Booking $booking) => [
                'title' => $booking->booking_code,
                'subtitle' => ucfirst((string) $booking->module).' · '.($booking->contact_name ?: 'Guest').' · ₹'.number_format((float) $booking->total_amount, 0),
                'url' => route('admin.bookings.show', $booking),
                'icon' => 'receipt_long',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchFlights(string $query): array
    {
        if (! Schema::hasTable('flights')) {
            return [];
        }

        return Flight::query()
            ->where(function ($builder) use ($query) {
                $builder->where('airline', 'like', $this->like($query))
                    ->orWhere('flight_number', 'like', $this->like($query))
                    ->orWhere('from_city', 'like', $this->like($query))
                    ->orWhere('to_city', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (Flight $flight) => [
                'title' => trim($flight->airline.' '.$flight->flight_number),
                'subtitle' => $flight->from_city.' → '.$flight->to_city,
                'url' => route('admin.flights.edit', $flight),
                'icon' => 'flight',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchHotels(string $query): array
    {
        if (! Schema::hasTable('hotels')) {
            return [];
        }

        return Hotel::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', $this->like($query))
                    ->orWhere('city', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (Hotel $hotel) => [
                'title' => $hotel->name,
                'subtitle' => $hotel->city.' · '.$hotel->star_rating.'★',
                'url' => route('admin.hotels.edit', $hotel),
                'icon' => 'hotel',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchPackages(string $query): array
    {
        if (! Schema::hasTable('travel_packages')) {
            return [];
        }

        return TravelPackage::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', $this->like($query))
                    ->orWhere('destination', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (TravelPackage $package) => [
                'title' => $package->name,
                'subtitle' => $package->destination.' · '.$package->duration_days.' days',
                'url' => route('admin.travel-packages.edit', $package),
                'icon' => 'luggage',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchPages(string $query): array
    {
        if (! Schema::hasTable('cms_pages')) {
            return [];
        }

        return Page::query()
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', $this->like($query))
                    ->orWhere('slug', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (Page $page) => [
                'title' => $page->title,
                'subtitle' => '/'.$page->slug.($page->is_active ? '' : ' · Draft'),
                'url' => route('admin.pages.edit', $page),
                'icon' => 'article',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchBlogs(string $query): array
    {
        if (! Schema::hasTable('blogs')) {
            return [];
        }

        return Blog::query()
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', $this->like($query))
                    ->orWhere('slug', 'like', $this->like($query))
                    ->orWhere('category', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (Blog $blog) => [
                'title' => $blog->title,
                'subtitle' => Str::limit((string) ($blog->category ?: 'Blog post'), 60),
                'url' => route('admin.blogs.edit', $blog),
                'icon' => 'newspaper',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchUsers(string $query): array
    {
        if (! Schema::hasTable('users')) {
            return [];
        }

        return User::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', $this->like($query))
                    ->orWhere('email', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (User $user) => [
                'title' => $user->name,
                'subtitle' => $user->email,
                'url' => route('admin.users.show', $user),
                'icon' => 'person',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchCoupons(string $query): array
    {
        if (! Schema::hasTable('coupons')) {
            return [];
        }

        return Coupon::query()
            ->where('code', 'like', $this->like($query))
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (Coupon $coupon) => [
                'title' => $coupon->code,
                'subtitle' => strtoupper((string) $coupon->discount_type).' · '.$coupon->discount_value,
                'url' => route('admin.coupons.edit', $coupon),
                'icon' => 'sell',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchTestimonials(string $query): array
    {
        if (! Schema::hasTable('testimonials')) {
            return [];
        }

        return Testimonial::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', $this->like($query))
                    ->orWhere('designation', 'like', $this->like($query))
                    ->orWhere('review', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (Testimonial $testimonial) => [
                'title' => $testimonial->name,
                'subtitle' => Str::limit((string) ($testimonial->designation ?: $testimonial->review), 70),
                'url' => route('admin.testimonials.edit', $testimonial),
                'icon' => 'reviews',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function searchContactInquiries(string $query): array
    {
        if (! Schema::hasTable('contact_inquiries')) {
            return [];
        }

        return ContactInquiry::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', $this->like($query))
                    ->orWhere('email', 'like', $this->like($query))
                    ->orWhere('subject', 'like', $this->like($query))
                    ->orWhere('message', 'like', $this->like($query));
            })
            ->orderByDesc('id')
            ->limit(self::MAX_PER_GROUP)
            ->get()
            ->map(fn (ContactInquiry $inquiry) => [
                'title' => $inquiry->subject ?: $inquiry->name,
                'subtitle' => $inquiry->name.' · '.$inquiry->email,
                'url' => route('admin.contact-inquiries.show', $inquiry),
                'icon' => 'inbox',
            ])
            ->all();
    }

    private function like(string $query): string
    {
        return '%'.$query.'%';
    }
}
