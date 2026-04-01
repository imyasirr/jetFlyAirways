<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Jet Fly Airways — Book Flights, Hotels & Holidays')</title>
    <meta name="description" content="@yield('meta_description', 'Jet Fly Airways — search flights, hotels, buses, trains, cabs and holiday packages with live inventory.')">
    @if(isset($siteSeo) && $siteSeo && (request()->routeIs('home') || request()->routeIs('welcome')))
        @if($siteSeo->keywords)<meta name="keywords" content="{{ $siteSeo->keywords }}">@endif
        @if($siteSeo->canonical_url)<link rel="canonical" href="{{ $siteSeo->canonical_url }}">@endif
        @if($siteSeo->robots)<meta name="robots" content="{{ $siteSeo->robots }}">@endif
        @if($siteSeo->og_title)<meta property="og:title" content="{{ $siteSeo->og_title }}">@endif
        @if($siteSeo->og_description)<meta property="og:description" content="{{ $siteSeo->og_description }}">@endif
        @php $ogImageResolved = \App\Support\PublicImageStorage::url($siteSeo->og_image); @endphp
        @if($ogImageResolved)<meta property="og:image" content="{{ $ogImageResolved }}">@endif
        @if($siteSeo->schema_markup)
            <script type="application/ld+json">{!! $siteSeo->schema_markup !!}</script>
        @endif
    @endif
    <style>
        :root {
            --primary:#0b2f71; --secondary:#38bdf8; --accent:#f97316; --bg:#f1f5f9; --card:#fff;
            --text:#0f172a; --muted:#64748b; --border:#e2e8f0; --header-h:72px;
        }
        * { box-sizing:border-box; }
        body { margin:0; font-family:system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif; background:var(--bg); color:var(--text); line-height:1.55; }
        a { color:inherit; text-decoration:none; }
        .container { width:min(1200px, 94%); margin:0 auto; }

        .topstrip { background:#0a1628; color:#94a3b8; font-size:12px; padding:8px 0; border-bottom:1px solid rgba(148,163,184,.15); }
        .topstrip-inner { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
        .topstrip-left { letter-spacing:.02em; }
        .topstrip-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        .topstrip-link { color:#e2e8f0; font-weight:600; }
        .topstrip-link:hover { color:#fff; text-decoration:underline; }
        .topstrip-sep { opacity:.4; }

        .site-header { background:#fff; position:sticky; top:0; z-index:100; box-shadow:0 4px 24px rgba(15,23,42,.07); border-bottom:1px solid var(--border); }
        .header-inner { display:flex; align-items:center; align-content:center; gap:10px 14px; padding:12px 0; min-height:0; flex-wrap:wrap; justify-content:space-between; row-gap:10px; }
        .brand-block { display:flex; flex-direction:column; gap:2px; min-width:0; flex:0 1 auto; max-width:min(100%,220px); }
        .brand { font-weight:800; font-size:clamp(1rem,2.5vw,1.15rem); letter-spacing:-.02em; color:var(--primary); line-height:1.15; }
        .brand-tagline { font-size:10px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
        .mega-nav {
            flex:1 1 260px;
            min-width:0;
            max-width:100%;
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            align-content:center;
            justify-content:center;
            gap:4px 6px;
            padding:4px 0;
            overflow:visible;
        }
        .mega-link { padding:7px 10px; border-radius:10px; font-size:12px; font-weight:700; color:#334155; align-self:center; line-height:1.2; }
        .mega-link:hover { background:#f1f5f9; color:var(--primary); }
        .mega-link.is-active { background:rgba(11,47,113,.1); color:var(--primary); }
        .mega-wrap { position:relative; flex:0 0 auto; align-self:center; display:flex; align-items:center; }
        .mega-trigger { display:flex; align-items:center; gap:6px; padding:7px 10px; border:none; border-radius:10px; font-size:12px; font-weight:700; color:#334155; background:transparent; cursor:pointer; font-family:inherit; line-height:1.2; }
        .mega-trigger:hover, .mega-wrap:hover .mega-trigger { background:#f1f5f9; color:var(--primary); }
        .mega-caret { font-size:9px; opacity:.65; }
        .mega-panel { display:none; position:absolute; left:0; top:100%; margin-top:2px; min-width:300px; max-width:min(440px,94vw); background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:0 20px 50px rgba(15,23,42,.14); padding:12px; z-index:220; }
        .mega-wrap:hover .mega-panel, .mega-wrap:focus-within .mega-panel { display:block; }
        .mega-cols { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:4px; }
        .mega-cols a { display:block; padding:10px 12px; border-radius:10px; font-size:14px; font-weight:600; color:#334155; }
        .mega-cols a:hover { background:#f8fafc; color:var(--primary); }
        .mega-cols a.is-active { background:rgba(11,47,113,.08); color:var(--primary); }
        .header-actions { display:flex; align-items:center; gap:8px; flex:0 0 auto; flex-wrap:wrap; justify-content:flex-end; max-width:100%; }
        .btn-header { background:var(--accent); color:#fff; padding:10px 18px; border-radius:12px; font-weight:800; font-size:14px; box-shadow:0 4px 14px rgba(249,115,22,.35); }
        .btn-header:hover { filter:brightness(1.06); }
        .btn-header-secondary { background:#fff; color:var(--primary); border:2px solid var(--border); padding:8px 16px; border-radius:12px; font-weight:700; font-size:13px; }
        .btn-header-secondary:hover { border-color:#cbd5e1; background:#f8fafc; }

        .row { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; }
        .card { background:var(--card); border-radius:16px; padding:20px; box-shadow:0 4px 24px rgba(11,47,113,.06); border:1px solid rgba(11,47,113,.07); }
        .grid { display:grid; gap:16px; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); }
        .grid-tight { grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); }
        .card-link { text-align:center; padding:16px; font-weight:700; color:var(--primary); transition:transform .12s, box-shadow .12s; }
        .card-link:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(11,47,113,.12); }
        .empty-hint { color:var(--muted); }
        .card-title { margin:0 0 8px; font-size:1rem; font-weight:800; color:var(--text); }
        .card-title-lg { margin:0 0 10px; font-size:1.05rem; color:var(--primary); }
        .card-meta { margin:0; font-size:14px; color:var(--muted); }
        .card-price { margin:8px 0 0; font-weight:800; color:var(--primary); }
        .card-list { margin:0 0 14px; padding-left:18px; font-size:14px; color:var(--muted); }
        .btn-block { display:block; text-align:center; margin-top:12px; }
        .btn { background:var(--accent); color:#fff; border:none; border-radius:12px; padding:11px 18px; font-weight:700; cursor:pointer; display:inline-block; font-size:15px; transition:transform .15s, box-shadow .15s; }
        .btn:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(249,115,22,.35); }
        .btn.secondary { background:var(--secondary); color:#0b2f71; }
        .btn.secondary:hover { box-shadow:0 6px 20px rgba(56,189,248,.35); }
        .btn.ghost { background:transparent; border:2px solid #fff; color:#fff; }
        main { padding:0 0 40px; min-height:calc(100vh - 200px); }
        .site-footer { margin-top:48px; background:#0f172a; color:#cbd5e1; font-size:14px; }
        .footer-main { padding:48px 0 32px; border-bottom:1px solid rgba(148,163,184,.12); }
        .footer-grid { display:grid; gap:32px; grid-template-columns:1.4fr repeat(3, 1fr); align-items:start; }
        @media (max-width:900px) { .footer-grid { grid-template-columns:1fr 1fr; } }
        @media (max-width:560px) { .footer-grid { grid-template-columns:1fr; } }
        .footer-logo { font-weight:800; font-size:1.15rem; color:#f8fafc; display:block; margin-bottom:12px; letter-spacing:-.02em; }
        .footer-desc { margin:0; font-size:14px; line-height:1.6; color:#94a3b8; max-width:40ch; }
        .footer-social { margin-top:18px; font-size:13px; color:#64748b; }
        .footer-social-label { font-weight:700; color:#94a3b8; margin-right:8px; }
        .footer-heading { margin:0 0 14px; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; }
        .footer-links { list-style:none; margin:0; padding:0; }
        .footer-links li { margin-bottom:10px; }
        .footer-links a { color:#cbd5e1; font-weight:500; }
        .footer-links a:hover { color:#fff; }
        .footer-bottom { padding:18px 0; font-size:12px; color:#64748b; }
        .footer-bottom-inner { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; }
        .footer-badges { text-align:right; max-width:52ch; line-height:1.5; }
        input, select, textarea { width:100%; padding:11px 12px; border:1px solid #c9d5ef; border-radius:10px; font-size:15px; }
        input:focus, select:focus, textarea:focus { outline:2px solid var(--secondary); border-color:transparent; }
        .section-title { margin:0 0 14px 0; color:var(--primary); font-size:1.35rem; font-weight:800; letter-spacing:-.02em; }
        .section-title-spaced { margin-top:28px; }
        .section-title-spaced-lg { margin-top:36px; }
        .hero { background:linear-gradient(135deg,#0b2f71 0%,#1e4a8a 42%,#0c4a6e 100%); color:#fff; padding:40px 0 48px; margin-bottom:0; }
        .hero h1 { margin:0 0 10px; font-size:clamp(1.65rem,4vw,2.35rem); font-weight:800; line-height:1.15; letter-spacing:-.02em; }
        .hero p { margin:0; opacity:.92; font-size:1.05rem; max-width:52ch; line-height:1.55; }
        .search-shell { margin-top:-36px; position:relative; z-index:10; }
        .search-panel { padding:0; overflow:hidden; }
        .search-panel-head { padding:16px 20px 12px; border-bottom:1px solid #e8eef8; }
        .search-panel-head h2 { margin:0 0 4px; font-size:1.15rem; color:var(--primary); font-weight:800; }
        .search-panel-head p { margin:0; font-size:14px; color:var(--muted); }
        .search-details { border-bottom:1px solid #e8eef8; }
        .search-details:last-child { border-bottom:none; }
        .search-details summary { padding:16px 20px; cursor:pointer; font-weight:800; color:var(--primary); list-style:none; font-size:15px; }
        .search-details summary::-webkit-details-marker { display:none; }
        .search-details[open] summary { border-bottom:1px solid #f1f5f9; }
        .search-body { padding:20px; }
        .search-grid { display:grid; gap:12px; grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); align-items:end; }
        .search-grid label { font-size:12px; font-weight:600; color:var(--muted); display:block; margin-bottom:4px; }
        .pill { display:inline-block; padding:6px 12px; border-radius:999px; background:rgba(255,255,255,.14); font-size:12px; font-weight:700; margin-bottom:10px; letter-spacing:.02em; }
        .feature-row { display:grid; gap:20px; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); margin-top:28px; }
        .feature-row-spaced { margin-top:36px; }
        .feature-row .card h3 { margin:0 0 8px; font-size:1rem; color:var(--primary); font-weight:800; }
        .feature-row .card p { margin:0; font-size:14px; color:var(--muted); line-height:1.55; }
        @media (max-width:960px) {
            .header-inner { justify-content:center; }
            .brand-block { flex-basis:100%; max-width:100%; text-align:center; }
            .mega-nav { flex-basis:100%; order:3; justify-content:center; }
            .header-actions { flex-basis:100%; order:2; justify-content:center; }
            .mega-panel { position:fixed; left:8px; right:8px; max-width:none; top:auto; }
            .mega-cols { grid-template-columns:1fr; }
        }

        .home-banner-page { margin-top:-44px; padding:0 0 20px; position:relative; z-index:12; }
        .home-banner-ribbon {
            position:relative;
            width:100%;
            margin:0;
            padding:0;
        }
        .home-banner-shell { width:100%; margin:0; }
        .home-banner-viewport {
            border-radius:22px; overflow:hidden;
            box-shadow:0 28px 60px -12px rgba(15,23,42,.45), 0 0 0 1px rgba(255,255,255,.2);
            background:#0f172a;
        }
        html[data-theme="dark"] .home-banner-viewport {
            box-shadow:0 28px 50px -12px rgba(0,0,0,.55), 0 0 0 1px rgba(148,163,184,.2);
        }
        .home-banner-track-inner {
            display:flex; overflow-x:auto; scroll-snap-type:x mandatory; scroll-behavior:smooth;
            -webkit-overflow-scrolling:touch;
            scrollbar-width:none;
        }
        .home-banner-track-inner::-webkit-scrollbar { display:none; }
        .home-banner-pane {
            flex:0 0 100%; scroll-snap-align:start; scroll-snap-stop:always;
            position:relative; aspect-ratio:2.6 / 1; min-height:clamp(168px, 28vw, 320px); max-height:360px;
        }
        .home-banner-link { display:block; height:100%; position:relative; color:inherit; }
        .home-banner-img {
            width:100%; height:100%; object-fit:cover; object-position:center;
            display:block; vertical-align:middle;
        }
        .home-banner-overlay {
            position:absolute; inset:0;
            background:linear-gradient(to top, rgba(15,23,42,.92) 0%, rgba(15,23,42,.25) 45%, transparent 72%);
            display:flex; align-items:flex-end; justify-content:flex-start;
            padding:clamp(14px,3vw,28px) clamp(16px,4vw,32px);
            pointer-events:none;
        }
        .home-banner-title {
            color:#fff; font-size:clamp(1.05rem,2.8vw,1.65rem); font-weight:800; line-height:1.2;
            letter-spacing:-.02em; text-shadow:0 2px 20px rgba(0,0,0,.45);
            max-width:min(42ch, 90%);
        }
        .home-banner-dots {
            display:flex; justify-content:center; gap:8px; margin-top:14px; flex-wrap:wrap;
        }
        .home-banner-dot {
            width:9px; height:9px; border-radius:999px; border:none; padding:0; cursor:pointer;
            background:rgba(15,23,42,.22); transition:transform .15s, background .15s, width .2s;
        }
        html[data-theme="dark"] .home-banner-dot { background:rgba(248,250,252,.25); }
        .home-banner-dot.is-active {
            background:var(--accent); width:26px; border-radius:999px;
        }
        @media (max-width:640px) {
            .home-banner-pane { aspect-ratio:16 / 9; min-height:180px; max-height:260px; }
        }

        .theme-toggle { background:transparent; border:1px solid rgba(148,163,184,.35); color:#e2e8f0; border-radius:8px; padding:4px 10px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; }
        .theme-toggle:hover { color:#fff; border-color:#94a3b8; }
        .locale-switch a { font-weight:700; }
        .locale-switch a.is-active { text-decoration:underline; }

        html[data-theme="dark"] {
            color-scheme: dark;
        }
        html[data-theme="dark"] body {
            --primary:#93c5fd; --secondary:#38bdf8; --accent:#fb923c; --bg:#0f172a; --card:#1e293b;
            --text:#f1f5f9; --muted:#94a3b8; --border:#334155;
        }
        html[data-theme="dark"] .site-header { background:var(--card); border-bottom-color:var(--border); }
        html[data-theme="dark"] .mega-link { color:#cbd5e1; }
        html[data-theme="dark"] .mega-link:hover { background:#334155; color:#fff; }
        html[data-theme="dark"] .mega-trigger { color:#cbd5e1; }
        html[data-theme="dark"] .mega-panel { background:var(--card); border-color:var(--border); }
        html[data-theme="dark"] .btn-header-secondary { background:var(--card); color:var(--primary); border-color:var(--border); }

        .wa-float {
            position:fixed; right:18px; bottom:22px; z-index:300;
            width:54px; height:54px; border-radius:50%; background:#25d366; color:#fff;
            display:flex; align-items:center; justify-content:center; font-size:28px;
            box-shadow:0 8px 28px rgba(37,211,102,.45);
        }
        .wa-float:hover { filter:brightness(1.08); color:#fff; }
        .notif-badge { display:inline-block; min-width:1.1em; padding:2px 6px; border-radius:999px; background:#f97316; color:#fff; font-size:11px; font-weight:800; vertical-align:middle; margin-left:4px; }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.welcome-popup')
    @include('partials.public-header')
    <main>
        @yield('full')
        <div class="container">
            @yield('content')
        </div>
    </main>
    @include('partials.public-footer')
    @include('partials.whatsapp-float')
    @include('partials.flash-swal', ['swalConfirmColor' => '#0b2f71'])
    <script>
        (function () {
            var key = 'jetfly-theme';
            var root = document.documentElement;
            var saved = localStorage.getItem(key);
            if (saved === 'dark' || saved === 'light') {
                root.setAttribute('data-theme', saved);
            }
            document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    root.setAttribute('data-theme', next);
                    localStorage.setItem(key, next);
                });
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
