<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Jet Fly Admin</title>
    <style>
        :root {
            --admin-bg:#f1f5f9; --admin-sidebar:#0f172a; --admin-sidebar-hover:#1e293b;
            --admin-accent:#f97316; --admin-primary:#0b2f71; --admin-muted:#64748b; --admin-border:#e2e8f0;
        }
        * { box-sizing:border-box; }
        body.admin-body {
            margin:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; background:var(--admin-bg); color:#0f172a;
            height:100vh; overflow:hidden;
        }
        a { color:inherit; text-decoration:none; }
        .admin-shell { display:flex; height:100%; min-height:0; }
        .admin-sidebar {
            width:268px; flex-shrink:0; background:var(--admin-sidebar); color:#e2e8f0;
            display:flex; flex-direction:column; height:100%; min-height:0; overflow:hidden;
            border-right:1px solid rgba(148,163,184,.12);
        }
        .admin-sidebar-brand {
            flex-shrink:0;
            padding:18px 16px; font-weight:800; font-size:1.05rem; letter-spacing:.02em; border-bottom:1px solid rgba(148,163,184,.15);
        }
        .admin-sidebar-brand span { display:block; font-size:11px; font-weight:600; color:#94a3b8; margin-top:4px; text-transform:uppercase; letter-spacing:.08em; }
        .admin-nav {
            flex:1; min-height:0; overflow-y:auto; overflow-x:hidden;
            padding:10px 10px 16px;
            -webkit-overflow-scrolling:touch;
            scrollbar-width:thin; scrollbar-color:rgba(148,163,184,.4) transparent;
        }
        .admin-nav::-webkit-scrollbar { width:6px; }
        .admin-nav::-webkit-scrollbar-thumb { background:rgba(148,163,184,.35); border-radius:6px; }
        .admin-nav a {
            display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; font-size:14px; font-weight:600; color:#cbd5e1; margin-bottom:4px;
        }
        .admin-nav a:hover { background:var(--admin-sidebar-hover); color:#fff; }
        .admin-nav a.active { background:rgba(249,115,22,.2); color:#fff; border:1px solid rgba(249,115,22,.35); }
        .admin-nav .nav-group {
            font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#64748b;
            padding:16px 12px 6px; margin-top:4px;
        }
        .admin-nav .nav-group:first-of-type { padding-top:4px; margin-top:0; }
        .admin-sidebar-foot { flex-shrink:0; padding:14px 16px; border-top:1px solid rgba(148,163,184,.15); font-size:13px; background:rgba(0,0,0,.12); }
        .admin-sidebar-foot a { color:#94a3b8; }
        .admin-sidebar-foot a:hover { color:#fff; }
        .admin-main { flex:1; display:flex; flex-direction:column; min-width:0; min-height:0; overflow:hidden; }
        .admin-topbar {
            flex-shrink:0;
            background:#fff; border-bottom:1px solid var(--admin-border); padding:14px 24px;
            display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
        }
        .admin-topbar h1 { margin:0; font-size:1.15rem; font-weight:800; color:var(--admin-primary); }
        .admin-user { display:flex; align-items:center; gap:12px; font-size:14px; color:var(--admin-muted); }
        .admin-user .email { max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .btn-logout {
            background:transparent; border:1px solid var(--admin-border); border-radius:10px; padding:8px 14px; font-weight:600; cursor:pointer; font-size:13px; color:#334155;
        }
        .btn-logout:hover { background:#f8fafc; border-color:#cbd5e1; }
        .admin-content { flex:1; min-height:0; overflow-y:auto; padding:24px; -webkit-overflow-scrolling:touch; }
        .admin-footer { flex-shrink:0; padding:12px 24px; border-top:1px solid var(--admin-border); background:#fff; font-size:13px; color:var(--admin-muted); text-align:center; }
        .admin-card { background:#fff; border-radius:14px; padding:20px 22px; border:1px solid var(--admin-border); box-shadow:0 1px 3px rgba(15,23,42,.06); }
        .admin-card h2 { margin:0 0 12px; font-size:1.1rem; color:var(--admin-primary); }
        .btn { display:inline-block; background:var(--admin-accent); color:#fff; border:none; border-radius:10px; padding:10px 16px; font-weight:700; cursor:pointer; font-size:14px; }
        .btn.secondary { background:#0ea5e9; color:#fff; }
        .btn.ghost { background:#f1f5f9; color:#334155; border:1px solid var(--admin-border); }
        table.admin-table { width:100%; border-collapse:collapse; font-size:14px; }
        table.admin-table th, table.admin-table td { text-align:left; padding:10px 12px; border-bottom:1px solid var(--admin-border); }
        table.admin-table th { color:var(--admin-muted); font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.04em; }
        .admin-content .card {
            background:#fff; border-radius:16px; padding:20px;
            box-shadow:0 10px 40px rgba(11,47,113,.08); border:1px solid rgba(11,47,113,.06);
        }
        .admin-content .row { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; }
        .admin-content .section-title { margin:0 0 14px 0; color:var(--admin-primary); font-size:1.35rem; font-weight:800; }
        .admin-content .btn.secondary { background:#0ea5e9; color:#fff; }
        .admin-content .btn.secondary:hover { filter:brightness(1.05); }
        .admin-content input, .admin-content select, .admin-content textarea {
            width:100%; padding:11px 12px; border:1px solid #c9d5ef; border-radius:10px; font-size:15px;
        }
        .admin-content input:focus, .admin-content select:focus, .admin-content textarea:focus {
            outline:2px solid #38bdf8; border-color:transparent;
        }
        @media (max-width:900px) {
            body.admin-body { height:auto; min-height:100vh; overflow:auto; }
            .admin-shell { flex-direction:column; height:auto; min-height:100vh; }
            .admin-sidebar { width:100%; height:auto; max-height:none; border-right:none; border-bottom:1px solid rgba(148,163,184,.15); }
            .admin-nav {
                display:grid; grid-template-columns:repeat(auto-fill,minmax(148px,1fr)); gap:4px;
                max-height:min(48vh,380px); flex:none;
            }
            .admin-main { overflow:visible; min-height:0; flex:1; }
            .admin-content { overflow:visible; flex:none; }
        }
    </style>
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

                <div class="nav-group">Catalog</div>
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

                <div class="nav-group">Website &amp; content</div>
                <a href="{{ route('admin.menu-items.index') }}" class="{{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">Header &amp; footer menu</a>
                <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">CMS pages</a>
                <a href="{{ route('admin.site-seo.edit') }}" class="{{ request()->routeIs('admin.site-seo.*') ? 'active' : '' }}">Site SEO</a>
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
                @php
                    $defaultHeading = match (true) {
                        request()->routeIs('admin.dashboard') => 'Dashboard',
                        request()->routeIs('admin.flights.*') => 'Flights',
                        request()->routeIs('admin.hotels.*') => 'Hotels',
                        request()->routeIs('admin.travel-packages.*') => 'Holiday packages',
                        request()->routeIs('admin.bus-routes.*') => 'Bus routes',
                        request()->routeIs('admin.train-routes.*') => 'Train routes',
                        request()->routeIs('admin.cab-services.*') => 'Cab services',
                        request()->routeIs('admin.travel-addons.*') => 'Visa & insurance',
                        request()->routeIs('admin.bookings.*') => 'Bookings',
                        request()->routeIs('admin.menu-items.*') => 'Website menu',
                        request()->routeIs('admin.pages.*') => 'CMS pages',
                        request()->routeIs('admin.coupons.*') => 'Coupons',
                        request()->routeIs('admin.offers.*') => 'Offers',
                        request()->routeIs('admin.popup-messages.*') => 'Welcome popups',
                        request()->routeIs('admin.blogs.*') => 'Blog',
                        request()->routeIs('admin.faqs.*') => 'FAQs',
                        request()->routeIs('admin.testimonials.*') => 'Testimonials',
                        request()->routeIs('admin.site-seo.*') => 'Site SEO',
                        request()->routeIs('admin.banners.*') => 'Home banners',
                        request()->routeIs('admin.home-sections.*') => 'Home sections',
                        request()->routeIs('admin.announcements.*') => 'Announcements',
                        request()->routeIs('admin.careers.*') => 'Vacancies',
                        request()->routeIs('admin.career-applications.*') => 'Job applications',
                        request()->routeIs('admin.contact-inquiries.*') => 'Contact inquiries',
                        request()->routeIs('admin.users.*') => 'Users',
                        default => 'Admin',
                    };
                @endphp
                <h1>@yield('heading', $defaultHeading)</h1>
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
    @include('partials.flash-swal', ['swalConfirmColor' => '#f97316'])
</body>
</html>
