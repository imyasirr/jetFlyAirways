<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My account') — Jet Fly</title>
    <style>
        :root { --acct-primary:#0c4a6e; --acct-accent:#0d9488; --acct-bg:#f0fdfa; --acct-border:#ccfbf1; --acct-muted:#64748b; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif; background:var(--acct-bg); color:#0f172a; min-height:100vh; }
        a { color:inherit; text-decoration:none; }
        .acct-shell { display:flex; min-height:100vh; }
        .acct-sidebar { width:250px; background:#fff; border-right:1px solid var(--acct-border); padding:20px 0; flex-shrink:0; position:sticky; top:0; align-self:flex-start; min-height:100vh; }
        .acct-sidebar a { display:block; padding:10px 20px; font-size:14px; font-weight:600; color:#334155; border-left:3px solid transparent; }
        .acct-sidebar a:hover { background:#f0fdfa; color:var(--acct-primary); }
        .acct-sidebar a.is-active { border-left-color:var(--acct-accent); background:#ecfdf5; color:var(--acct-primary); }
        .acct-sidebar .group { font-size:11px; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; padding:16px 20px 8px; }
        .acct-main { flex:1; min-width:0; display:flex; flex-direction:column; }
        .acct-top { background:#fff; border-bottom:1px solid var(--acct-border); padding:16px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
        .acct-top h1 { margin:0; font-size:1.2rem; color:var(--acct-primary); }
        .acct-top a.site-link { font-size:14px; color:var(--acct-accent); font-weight:600; }
        .acct-body { padding:24px; flex:1; }
        .acct-card { background:#fff; border-radius:14px; padding:22px; border:1px solid var(--acct-border); box-shadow:0 2px 12px rgba(13,148,136,.06); margin-bottom:20px; }
        .acct-card h2 { margin:0 0 14px; font-size:1.05rem; color:var(--acct-primary); }
        .acct-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:14px; margin-bottom:24px; }
        .acct-stat { background:#fff; border:1px solid var(--acct-border); border-radius:12px; padding:16px; text-align:center; }
        .acct-stat strong { display:block; font-size:1.4rem; color:var(--acct-accent); }
        .acct-stat span { font-size:12px; color:var(--acct-muted); text-transform:uppercase; letter-spacing:.04em; }
        table.acct-table { width:100%; border-collapse:collapse; font-size:14px; }
        table.acct-table th, table.acct-table td { text-align:left; padding:10px 12px; border-bottom:1px solid var(--acct-border); }
        table.acct-table th { color:var(--acct-muted); font-size:12px; text-transform:uppercase; letter-spacing:.04em; }
        .btn { display:inline-block; background:var(--acct-accent); color:#fff; border:none; border-radius:10px; padding:10px 18px; font-weight:700; cursor:pointer; font-size:14px; }
        .btn.outline { background:#fff; color:var(--acct-primary); border:2px solid var(--acct-border); }
        .btn.outline:hover { background:#f0fdfa; }
        label { display:block; font-size:13px; font-weight:600; color:var(--acct-muted); margin-bottom:6px; }
        input, textarea { width:100%; max-width:420px; padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:15px; margin-bottom:14px; }
        input:focus, textarea:focus { outline:2px solid var(--acct-accent); border-color:transparent; }
        .ticket { border:2px dashed var(--acct-border); border-radius:12px; padding:20px; background:linear-gradient(180deg,#fff 0%,#f0fdfa 100%); }
        .ticket-code { font-size:1.5rem; font-weight:800; letter-spacing:.08em; color:var(--acct-primary); }
        .badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; background:#ecfdf5; color:#0f766e; }
        .pagination { margin-top:16px; font-size:14px; display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
        .pagination a, .pagination span { padding:6px 12px; border-radius:8px; border:1px solid var(--acct-border); background:#fff; }
        .pagination span[aria-current="page"] { background:#ecfdf5; border-color:var(--acct-accent); color:var(--acct-primary); font-weight:700; }
        @media (max-width:860px) { .acct-shell { flex-direction:column; } .acct-sidebar { width:100%; min-height:0; position:relative; display:flex; flex-wrap:wrap; gap:4px; padding:12px; } .acct-sidebar a { padding:8px 14px; border-radius:8px; border-left:none; } .acct-sidebar a.is-active { border:2px solid var(--acct-accent); } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="acct-shell">
        <aside class="acct-sidebar">
            <div class="group">Account</div>
            <a href="{{ route('account.dashboard') }}" class="{{ request()->routeIs('account.dashboard') ? 'is-active' : '' }}">Overview</a>
            <a href="{{ route('account.bookings.index') }}" class="{{ request()->routeIs('account.bookings.*') ? 'is-active' : '' }}">My bookings</a>
            <a href="{{ route('account.offers') }}" class="{{ request()->routeIs('account.offers') ? 'is-active' : '' }}">Offers &amp; discounts</a>
            <a href="{{ route('account.wishlist.index') }}" class="{{ request()->routeIs('account.wishlist.index') ? 'is-active' : '' }}">Wishlist</a>
            <a href="{{ route('account.announcements.index') }}" class="{{ request()->routeIs('account.announcements.index', 'account.announcements.read') ? 'is-active' : '' }}">Notifications</a>
            <div class="group">Settings</div>
            <a href="{{ route('account.profile.edit') }}" class="{{ request()->routeIs('account.profile.*') ? 'is-active' : '' }}">Profile</a>
            <a href="{{ route('account.password.edit') }}" class="{{ request()->routeIs('account.password.*') ? 'is-active' : '' }}">Password</a>
        </aside>
        <div class="acct-main">
            <header class="acct-top">
                <h1>@yield('heading', 'My account')</h1>
                <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                    <a href="{{ route('home') }}" class="site-link">← Back to website</a>
                    <form method="post" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn outline">Log out</button>
                    </form>
                </div>
            </header>
            <div class="acct-body">
                @yield('content')
            </div>
        </div>
    </div>
    @stack('scripts')
    @include('partials.flash-swal', ['swalConfirmColor' => '#0d9488'])
</body>
</html>
