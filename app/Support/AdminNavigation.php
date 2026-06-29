<?php

namespace App\Support;

use Illuminate\Support\Facades\Request;

class AdminNavigation
{
    /**
     * @return list<array{id: string, label: string, icon: string, match: list<string>, items: list<array{label: string, route: string, match: list<string>}>}>
     */
    public static function groups(): array
    {
        return [
            [
                'id' => 'dashboard',
                'label' => 'Dashboard',
                'icon' => 'space_dashboard',
                'match' => ['admin.dashboard'],
                'items' => [
                    ['label' => 'Overview', 'route' => 'admin.dashboard', 'match' => ['admin.dashboard']],
                ],
            ],
            [
                'id' => 'flights',
                'label' => 'Flights',
                'icon' => 'flight',
                'match' => ['admin.flights.*'],
                'items' => [
                    ['label' => 'All flights', 'route' => 'admin.flights.index', 'match' => ['admin.flights.index', 'admin.flights.show', 'admin.flights.edit']],
                    ['label' => 'Add flight', 'route' => 'admin.flights.create', 'match' => ['admin.flights.create']],
                ],
            ],
            [
                'id' => 'hotels',
                'label' => 'Hotels',
                'icon' => 'hotel',
                'match' => ['admin.hotels.*'],
                'items' => [
                    ['label' => 'All hotels', 'route' => 'admin.hotels.index', 'match' => ['admin.hotels.index', 'admin.hotels.show', 'admin.hotels.edit']],
                    ['label' => 'Add hotel', 'route' => 'admin.hotels.create', 'match' => ['admin.hotels.create']],
                ],
            ],
            [
                'id' => 'packages',
                'label' => 'Packages',
                'icon' => 'luggage',
                'match' => ['admin.travel-packages.*'],
                'items' => [
                    ['label' => 'All packages', 'route' => 'admin.travel-packages.index', 'match' => ['admin.travel-packages.index', 'admin.travel-packages.show', 'admin.travel-packages.edit']],
                    ['label' => 'Add package', 'route' => 'admin.travel-packages.create', 'match' => ['admin.travel-packages.create']],
                ],
            ],
            [
                'id' => 'buses',
                'label' => 'Buses',
                'icon' => 'directions_bus',
                'match' => ['admin.bus-routes.*'],
                'items' => [
                    ['label' => 'All routes', 'route' => 'admin.bus-routes.index', 'match' => ['admin.bus-routes.index', 'admin.bus-routes.show', 'admin.bus-routes.edit']],
                    ['label' => 'Add route', 'route' => 'admin.bus-routes.create', 'match' => ['admin.bus-routes.create']],
                ],
            ],
            [
                'id' => 'trains',
                'label' => 'Trains',
                'icon' => 'train',
                'match' => ['admin.train-routes.*'],
                'items' => [
                    ['label' => 'All routes', 'route' => 'admin.train-routes.index', 'match' => ['admin.train-routes.index', 'admin.train-routes.show', 'admin.train-routes.edit']],
                    ['label' => 'Add route', 'route' => 'admin.train-routes.create', 'match' => ['admin.train-routes.create']],
                ],
            ],
            [
                'id' => 'cabs',
                'label' => 'Cabs',
                'icon' => 'local_taxi',
                'match' => ['admin.cab-services.*'],
                'items' => [
                    ['label' => 'All services', 'route' => 'admin.cab-services.index', 'match' => ['admin.cab-services.index', 'admin.cab-services.show', 'admin.cab-services.edit']],
                    ['label' => 'Add service', 'route' => 'admin.cab-services.create', 'match' => ['admin.cab-services.create']],
                ],
            ],
            [
                'id' => 'addons',
                'label' => 'Visa & insurance',
                'icon' => 'travel_explore',
                'match' => ['admin.travel-addons.*'],
                'items' => [
                    ['label' => 'All add-ons', 'route' => 'admin.travel-addons.index', 'match' => ['admin.travel-addons.index', 'admin.travel-addons.edit']],
                    ['label' => 'Add add-on', 'route' => 'admin.travel-addons.create', 'match' => ['admin.travel-addons.create']],
                ],
            ],
            [
                'id' => 'commerce',
                'label' => 'Commerce',
                'icon' => 'payments',
                'match' => ['admin.bookings.*', 'admin.coupons.*', 'admin.offers.*', 'admin.reports.*'],
                'items' => [
                    ['label' => 'Bookings', 'route' => 'admin.bookings.index', 'match' => ['admin.bookings.*']],
                    ['label' => 'Coupons', 'route' => 'admin.coupons.index', 'match' => ['admin.coupons.*']],
                    ['label' => 'Offers', 'route' => 'admin.offers.index', 'match' => ['admin.offers.*']],
                    ['label' => 'Payment reports', 'route' => 'admin.reports.payments', 'match' => ['admin.reports.payments']],
                    ['label' => 'Booking reports', 'route' => 'admin.reports.bookings', 'match' => ['admin.reports.bookings']],
                ],
            ],
            [
                'id' => 'website',
                'label' => 'Website',
                'icon' => 'language',
                'match' => [
                    'admin.menu-items.*',
                    'admin.pages.*',
                    'admin.destination-guide.*',
                    'admin.site-seo.*',
                    'admin.site-settings.*',
                    'admin.integrations.*',
                    'admin.banners.*',
                    'admin.page-banners.*',
                    'admin.home-sections.*',
                    'admin.home-trust-cards.*',
                    'admin.popular-destinations.*',
                    'admin.popup-messages.*',
                    'admin.blogs.*',
                    'admin.faqs.*',
                    'admin.testimonials.*',
                    'admin.announcements.*',
                ],
                'items' => [
                    ['label' => 'Header & footer menu', 'route' => 'admin.menu-items.index', 'match' => ['admin.menu-items.*']],
                    ['label' => 'CMS pages', 'route' => 'admin.pages.index', 'match' => ['admin.pages.*']],
                    ['label' => 'Destination guide', 'route' => 'admin.destination-guide.edit', 'match' => ['admin.destination-guide.*']],
                    ['label' => 'Site SEO', 'route' => 'admin.site-seo.edit', 'match' => ['admin.site-seo.*']],
                    ['label' => 'Header & footer settings', 'route' => 'admin.site-settings.edit', 'match' => ['admin.site-settings.*']],
                    ['label' => 'API integrations', 'route' => 'admin.integrations.index', 'match' => ['admin.integrations.*']],
                    ['label' => 'Home banners', 'route' => 'admin.banners.index', 'match' => ['admin.banners.*']],
                    ['label' => 'Page banners', 'route' => 'admin.page-banners.index', 'match' => ['admin.page-banners.*']],
                    ['label' => 'Homepage sections', 'route' => 'admin.home-sections.index', 'match' => ['admin.home-sections.*']],
                    ['label' => 'Trust highlights', 'route' => 'admin.home-trust-cards.index', 'match' => ['admin.home-trust-cards.*']],
                    ['label' => 'Popular destinations', 'route' => 'admin.popular-destinations.index', 'match' => ['admin.popular-destinations.*']],
                    ['label' => 'Welcome popups', 'route' => 'admin.popup-messages.index', 'match' => ['admin.popup-messages.*']],
                    ['label' => 'Blog', 'route' => 'admin.blogs.index', 'match' => ['admin.blogs.*']],
                    ['label' => 'FAQs', 'route' => 'admin.faqs.index', 'match' => ['admin.faqs.*']],
                    ['label' => 'Testimonials', 'route' => 'admin.testimonials.index', 'match' => ['admin.testimonials.*']],
                    ['label' => 'Announcements', 'route' => 'admin.announcements.index', 'match' => ['admin.announcements.*']],
                ],
            ],
            [
                'id' => 'leads',
                'label' => 'Leads',
                'icon' => 'inbox',
                'match' => ['admin.careers.*', 'admin.career-applications.*', 'admin.contact-inquiries.*'],
                'items' => [
                    ['label' => 'Vacancies', 'route' => 'admin.careers.index', 'match' => ['admin.careers.*']],
                    ['label' => 'Job applications', 'route' => 'admin.career-applications.index', 'match' => ['admin.career-applications.*']],
                    ['label' => 'Contact inquiries', 'route' => 'admin.contact-inquiries.index', 'match' => ['admin.contact-inquiries.*']],
                ],
            ],
            [
                'id' => 'customers',
                'label' => 'Customers',
                'icon' => 'group',
                'match' => ['admin.users.*'],
                'items' => [
                    ['label' => 'All users', 'route' => 'admin.users.index', 'match' => ['admin.users.index', 'admin.users.show']],
                ],
            ],
        ];
    }

    /**
     * @return array{id: string, label: string, icon: string, match: list<string>, items: list<array{label: string, route: string, match: list<string>}>}
     */
    public static function activeGroup(): array
    {
        foreach (self::groups() as $group) {
            if (self::matches($group['match'])) {
                return $group;
            }
        }

        return self::groups()[0];
    }

    /**
     * @param  array{label: string, route: string, match: list<string>}  $item
     */
    public static function itemIsActive(array $item): bool
    {
        return self::matches($item['match']);
    }

    /**
     * @param  array{id: string, label: string, icon: string, match: list<string>, items: list<array{label: string, route: string, match: list<string>}>}  $group
     */
    public static function groupIsActive(array $group): bool
    {
        return self::activeGroup()['id'] === $group['id'];
    }

    /**
     * @param  array{id: string, label: string, icon: string, match: list<string>, items: list<array{label: string, route: string, match: list<string>}>}  $group
     */
    public static function groupHref(array $group): string
    {
        $route = $group['items'][0]['route'] ?? 'admin.dashboard';

        return route($route);
    }

    /**
     * @param  list<string>  $patterns
     */
    private static function matches(array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (Request::routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }
}
