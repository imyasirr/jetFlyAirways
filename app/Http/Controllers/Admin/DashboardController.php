<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Support\PaymentGatewaySettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'headline' => $this->headlineStats(),
            'inventoryStats' => $this->inventoryStats(),
            'cmsStats' => $this->cmsStats(),
            'commerceStats' => $this->commerceStats(),
            'leadStats' => $this->leadStats(),
            'cmsQuickLinks' => $this->cmsQuickLinks(),
            'recentBookings' => $this->recentBookings(),
            'paymentGateway' => PaymentGatewaySettings::statusSummary(),
        ]);
    }

    /**
     * @return list<array{label: string, value: string, hint: string, icon: string, route: ?string}>
     */
    private function headlineStats(): array
    {
        $paidRevenue = 0.0;
        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'payment_status')) {
            $paidRevenue = (float) DB::table('bookings')
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        return [
            [
                'label' => 'Total bookings',
                'value' => number_format($this->countTable('bookings')),
                'hint' => $this->countTable('bookings', fn ($q) => $q->where('payment_status', 'pending')).' awaiting payment',
                'icon' => 'receipt_long',
                'route' => 'admin.bookings.index',
            ],
            [
                'label' => 'Paid revenue',
                'value' => '₹'.number_format($paidRevenue, 0),
                'hint' => $this->countTable('bookings', fn ($q) => $q->where('payment_status', 'paid')).' paid bookings',
                'icon' => 'payments',
                'route' => 'admin.reports.payments',
            ],
            [
                'label' => 'Customers',
                'value' => number_format($this->countTable('users')),
                'hint' => 'Registered accounts',
                'icon' => 'group',
                'route' => 'admin.users.index',
            ],
            [
                'label' => 'CMS pages',
                'value' => number_format($this->countTable('cms_pages')),
                'hint' => $this->countTable('cms_pages', fn ($q) => $q->where('is_active', true)).' published',
                'icon' => 'article',
                'route' => 'admin.pages.index',
            ],
        ];
    }

    /**
     * @return list<array{label: string, count: int, meta: ?string, route: string, icon: string}>
     */
    private function inventoryStats(): array
    {
        return [
            ['label' => 'Flights', 'count' => $this->countTable('flights'), 'meta' => $this->activeMeta('flights', 'is_active'), 'route' => 'admin.flights.index', 'icon' => 'flight'],
            ['label' => 'Hotels', 'count' => $this->countTable('hotels'), 'meta' => $this->activeMeta('hotels', 'is_active'), 'route' => 'admin.hotels.index', 'icon' => 'hotel'],
            ['label' => 'Packages', 'count' => $this->countTable('travel_packages'), 'meta' => $this->activeMeta('travel_packages', 'is_published'), 'route' => 'admin.travel-packages.index', 'icon' => 'luggage'],
            ['label' => 'Bus routes', 'count' => $this->countTable('bus_routes'), 'meta' => $this->activeMeta('bus_routes', 'is_active'), 'route' => 'admin.bus-routes.index', 'icon' => 'directions_bus'],
            ['label' => 'Train routes', 'count' => $this->countTable('train_routes'), 'meta' => $this->activeMeta('train_routes', 'is_active'), 'route' => 'admin.train-routes.index', 'icon' => 'train'],
            ['label' => 'Cab services', 'count' => $this->countTable('cab_services'), 'meta' => $this->activeMeta('cab_services', 'is_active'), 'route' => 'admin.cab-services.index', 'icon' => 'local_taxi'],
            ['label' => 'Visa & insurance', 'count' => $this->countTable('travel_addons'), 'meta' => $this->activeMeta('travel_addons', 'is_active'), 'route' => 'admin.travel-addons.index', 'icon' => 'travel_explore'],
        ];
    }

    /**
     * @return list<array{label: string, count: int, meta: ?string, route: string, icon: string}>
     */
    private function cmsStats(): array
    {
        return [
            ['label' => 'CMS pages', 'count' => $this->countTable('cms_pages'), 'meta' => $this->activeMeta('cms_pages', 'is_active'), 'route' => 'admin.pages.index', 'icon' => 'article'],
            ['label' => 'Menu links', 'count' => $this->countTable('menu_items'), 'meta' => null, 'route' => 'admin.menu-items.index', 'icon' => 'menu'],
            ['label' => 'Home banners', 'count' => $this->countTable('banners'), 'meta' => $this->activeMeta('banners', 'is_active'), 'route' => 'admin.banners.index', 'icon' => 'view_carousel'],
            ['label' => 'Page banners', 'count' => $this->countTable('page_banners'), 'meta' => null, 'route' => 'admin.page-banners.index', 'icon' => 'wallpaper'],
            ['label' => 'Blog posts', 'count' => $this->countTable('blogs'), 'meta' => null, 'route' => 'admin.blogs.index', 'icon' => 'newspaper'],
            ['label' => 'FAQs', 'count' => $this->countTable('faqs'), 'meta' => $this->activeMeta('faqs', 'is_active'), 'route' => 'admin.faqs.index', 'icon' => 'quiz'],
            ['label' => 'Testimonials', 'count' => $this->countTable('testimonials'), 'meta' => $this->activeMeta('testimonials', 'is_active'), 'route' => 'admin.testimonials.index', 'icon' => 'reviews'],
            ['label' => 'Announcements', 'count' => $this->countTable('announcements'), 'meta' => $this->activeMeta('announcements', 'is_active'), 'route' => 'admin.announcements.index', 'icon' => 'campaign'],
            ['label' => 'Welcome popups', 'count' => $this->countTable('popup_messages'), 'meta' => $this->activeMeta('popup_messages', 'is_active'), 'route' => 'admin.popup-messages.index', 'icon' => 'web_asset'],
            ['label' => 'Trust highlights', 'count' => $this->countTable('home_trust_cards'), 'meta' => $this->activeMeta('home_trust_cards', 'is_active'), 'route' => 'admin.home-trust-cards.index', 'icon' => 'verified'],
            ['label' => 'Home sections', 'count' => $this->countTable('home_sections'), 'meta' => $this->activeMeta('home_sections', 'is_active'), 'route' => 'admin.home-sections.index', 'icon' => 'dashboard_customize'],
            ['label' => 'Offers strip', 'count' => $this->countTable('offers'), 'meta' => null, 'route' => 'admin.offers.index', 'icon' => 'local_offer'],
        ];
    }

    /**
     * @return list<array{label: string, count: int, meta: ?string, route: string, icon: string}>
     */
    private function commerceStats(): array
    {
        return [
            ['label' => 'Bookings', 'count' => $this->countTable('bookings'), 'meta' => null, 'route' => 'admin.bookings.index', 'icon' => 'receipt_long'],
            ['label' => 'Coupons', 'count' => $this->countTable('coupons'), 'meta' => null, 'route' => 'admin.coupons.index', 'icon' => 'sell'],
            ['label' => 'Offers', 'count' => $this->countTable('offers'), 'meta' => null, 'route' => 'admin.offers.index', 'icon' => 'percent'],
        ];
    }

    /**
     * @return list<array{label: string, count: int, meta: ?string, route: string, icon: string}>
     */
    private function leadStats(): array
    {
        return [
            ['label' => 'Contact inquiries', 'count' => $this->countTable('contact_inquiries'), 'meta' => null, 'route' => 'admin.contact-inquiries.index', 'icon' => 'inbox'],
            ['label' => 'Job applications', 'count' => $this->countTable('career_applications'), 'meta' => null, 'route' => 'admin.career-applications.index', 'icon' => 'badge'],
            ['label' => 'Open vacancies', 'count' => $this->countTable('careers'), 'meta' => $this->activeMeta('careers', 'is_hiring'), 'route' => 'admin.careers.index', 'icon' => 'work'],
        ];
    }

    /**
     * @return list<array{label: string, route: string, icon: string}>
     */
    private function cmsQuickLinks(): array
    {
        return [
            ['label' => 'Site settings', 'route' => 'admin.site-settings.edit', 'icon' => 'tune'],
            ['label' => 'Site SEO', 'route' => 'admin.site-seo.edit', 'icon' => 'search'],
            ['label' => 'Destination guide', 'route' => 'admin.destination-guide.edit', 'icon' => 'map'],
            ['label' => 'API integrations', 'route' => 'admin.integrations.index', 'icon' => 'hub'],
            ['label' => 'View website', 'route' => 'home', 'icon' => 'open_in_new'],
        ];
    }

    private function recentBookings()
    {
        if (! Schema::hasTable('bookings')) {
            return collect();
        }

        return Booking::query()->orderByDesc('id')->limit(8)->get();
    }

    private function countTable(string $table, ?callable $modifier = null): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);
        if ($modifier) {
            $modifier($query);
        }

        return (int) $query->count();
    }

    private function activeMeta(string $table, string $column): ?string
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return null;
        }

        $active = $this->countTable($table, fn ($q) => $q->where($column, true));

        return $active.' active';
    }
}
