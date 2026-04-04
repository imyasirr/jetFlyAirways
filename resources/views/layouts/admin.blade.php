<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $rn = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
        $segments = explode('.', $rn);
        $res = $segments[1] ?? '';
        $act = $segments[2] ?? 'index';
        $M = fn (string $p, string $s, string $d) => ['p' => $p, 's' => $s, 'd' => $d];
        $resMeta = [
            'flights' => $M('Flights', 'flight', 'Add and manage flight offers shown to visitors on your public site.'),
            'hotels' => $M('Hotels', 'hotel', 'Control hotel listings, prices, and visibility on the website.'),
            'travel-packages' => $M('Holiday packages', 'package', 'Publish and update holiday packages and destinations.'),
            'bus-routes' => $M('Bus routes', 'bus route', 'Maintain bus routes and fares for the booking catalogue.'),
            'train-routes' => $M('Train routes', 'train route', 'Maintain train routes and fares for the booking catalogue.'),
            'cab-services' => $M('Cab services', 'cab service', 'Manage cab types, cities, and pricing for ground transport.'),
            'travel-addons' => $M('Visa & insurance', 'add-on', 'Manage visa and travel insurance products shown on the site.'),
            'bookings' => $M('Bookings', 'booking', 'Review customer bookings, status, and payment details.'),
            'coupons' => $M('Coupons', 'coupon', 'Create discount codes and control when they are active.'),
            'offers' => $M('Offers', 'offer', 'Promotional offers displayed on the homepage and offer strips.'),
            'menu-items' => $M('Header & footer menu', 'menu link', 'Edit navigation links, new-tab behaviour, and login-only items.'),
            'pages' => $M('CMS pages', 'page', 'Create and publish static pages (About, Terms, etc.) for your site.'),
            'site-seo' => $M('Site SEO', 'SEO', 'Homepage meta tags, Open Graph, and structured data for search and social.'),
            'site-settings' => $M('Site header & footer', 'setting', 'Top bar text, logo lines, support contacts, footer copy, and social URLs on the public site.'),
            'banners' => $M('Home banners', 'banner', 'Promo carousel below the hero on / and /welcome — image, copy, and CTA links (separate from the single hero background in Site header & footer).'),
            'home-sections' => $M('Homepage sections', 'section', 'Turn homepage content blocks on or off and set their order.'),
            'popup-messages' => $M('Welcome popups', 'popup', 'Timed promotional popups for first-time or returning visitors.'),
            'blogs' => $M('Blog', 'blog post', 'Write and schedule articles; control featured posts and SEO fields.'),
            'faqs' => $M('FAQs', 'FAQ', 'Questions and answers shown on the public FAQ page.'),
            'testimonials' => $M('Testimonials', 'testimonial', 'Customer quotes displayed on the homepage and marketing areas.'),
            'announcements' => $M('Announcements', 'announcement', 'Site-wide notices and alert banners for visitors.'),
            'careers' => $M('Vacancies', 'vacancy', 'Job listings and whether you are accepting applications on /jobs.'),
            'career-applications' => $M('Job applications', 'application', 'CVs and messages submitted through the careers form.'),
            'contact-inquiries' => $M('Contact inquiries', 'inquiry', 'Messages sent from the public contact page.'),
            'users' => $M('Users', 'user', 'Registered customers and their roles for the booking area.'),
        ];
        if ($res === 'dashboard') {
            $defaultHeading = 'Dashboard';
            $defaultPageDescription = 'Overview of live inventory, recent bookings, and quick links to manage your travel site.';
        } elseif (isset($resMeta[$res])) {
            $m = $resMeta[$res];
            $defaultPageDescription = $m['d'];
            if ($res === 'site-seo') {
                $defaultHeading = 'Site SEO';
            } else {
                $defaultHeading = match ($act) {
                    'create' => 'Add '.$m['s'],
                    'edit' => 'Edit '.$m['s'],
                    'show' => $m['p'].' · details',
                    default => $m['p'],
                };
            }
        } else {
            $defaultHeading = 'Admin';
            $defaultPageDescription = 'Manage your Jet Fly Airways admin panel.';
        }
    @endphp
    <title>@yield('title', $defaultHeading) — Jet Fly Admin</title>
    <meta name="description" content="@yield('meta_description', $defaultPageDescription)">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=2">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar" aria-label="Admin navigation">
            <div class="admin-sidebar-brand">
                Jet Fly
                <span>Admin panel</span>
            </div>
            <nav class="admin-nav">
                <div class="nav-group">Overview</div>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>

                <div class="nav-group">Storefront catalogue</div>
                <a href="{{ route('admin.flights.index') }}" class="{{ request()->routeIs('admin.flights.*') ? 'active' : '' }}">Flights</a>
                <a href="{{ route('admin.hotels.index') }}" class="{{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">Hotels</a>
                <a href="{{ route('admin.travel-packages.index') }}" class="{{ request()->routeIs('admin.travel-packages.*') ? 'active' : '' }}">Holiday packages</a>
                <a href="{{ route('admin.bus-routes.index') }}" class="{{ request()->routeIs('admin.bus-routes.*') ? 'active' : '' }}">Bus routes</a>
                <a href="{{ route('admin.train-routes.index') }}" class="{{ request()->routeIs('admin.train-routes.*') ? 'active' : '' }}">Train routes</a>
                <a href="{{ route('admin.cab-services.index') }}" class="{{ request()->routeIs('admin.cab-services.*') ? 'active' : '' }}">Cab services</a>
                <a href="{{ route('admin.travel-addons.index') }}" class="{{ request()->routeIs('admin.travel-addons.*') ? 'active' : '' }}">Visa &amp; insurance</a>

                <div class="nav-group">Commerce</div>
                <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">Bookings</a>
                <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">Coupons</a>
                <a href="{{ route('admin.offers.index') }}" class="{{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">Offers</a>

                <div class="nav-group">Site &amp; marketing</div>
                <a href="{{ route('admin.menu-items.index') }}" class="{{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">Header &amp; footer menu</a>
                <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">CMS pages</a>
                <a href="{{ route('admin.site-seo.edit') }}" class="{{ request()->routeIs('admin.site-seo.*') ? 'active' : '' }}">Site SEO</a>
                <a href="{{ route('admin.site-settings.edit') }}" class="{{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}">Site header &amp; footer</a>
                <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">Home banners</a>
                <a href="{{ route('admin.home-sections.index') }}" class="{{ request()->routeIs('admin.home-sections.*') ? 'active' : '' }}">Homepage sections</a>
                <a href="{{ route('admin.popup-messages.index') }}" class="{{ request()->routeIs('admin.popup-messages.*') ? 'active' : '' }}">Welcome popups</a>
                <a href="{{ route('admin.blogs.index') }}" class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">Blog</a>
                <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">FAQs</a>
                <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">Testimonials</a>
                <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">Announcements</a>

                <div class="nav-group">Careers &amp; leads</div>
                <a href="{{ route('admin.careers.index') }}" class="{{ request()->routeIs('admin.careers.*') ? 'active' : '' }}">Vacancies</a>
                <a href="{{ route('admin.career-applications.index') }}" class="{{ request()->routeIs('admin.career-applications.*') ? 'active' : '' }}">Job applications</a>
                <a href="{{ route('admin.contact-inquiries.index') }}" class="{{ request()->routeIs('admin.contact-inquiries.*') ? 'active' : '' }}">Contact inquiries</a>

                <div class="nav-group">Customers</div>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
            </nav>
            <div class="admin-sidebar-foot">
                <a href="{{ route('home') }}" target="_blank" rel="noopener">View public site →</a>
            </div>
        </aside>
        <div class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar-main">
                    <h1>@yield('heading', $defaultHeading)</h1>
                    <p class="admin-page-description">@yield('page_description', $defaultPageDescription)</p>
                </div>
                <div class="admin-user">
                    <span class="email" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</span>
                    <form method="post" action="{{ route('admin.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout">Log out</button>
                    </form>
                </div>
            </header>
            <div class="admin-content">
                @yield('content')
            </div>
            <footer class="admin-footer">Jet Fly Airways · Admin · {{ date('Y') }}</footer>
        </div>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var nav = document.querySelector('.admin-nav');
            var active = nav && nav.querySelector('a.active');
            if (nav && active) {
                active.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            }
            if (typeof flatpickr === 'undefined') {
                return;
            }
            document.querySelectorAll('.admin-content input[type="date"]').forEach(function (el) {
                if (el._flatpickr) {
                    return;
                }
                flatpickr(el, { dateFormat: 'Y-m-d', allowInput: true, disableMobile: true });
            });
            document.querySelectorAll('.admin-content input[type="datetime-local"]').forEach(function (el) {
                if (el._flatpickr) {
                    return;
                }
                flatpickr(el, {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: 'Y-m-d\\TH:i',
                    allowInput: true,
                    disableMobile: true,
                    minuteIncrement: 1,
                });
            });
        });
    </script>
    @include('partials.flash-swal', ['swalConfirmColor' => '#f97316'])
</body>
</html>
