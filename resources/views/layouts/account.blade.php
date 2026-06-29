<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.seo-head', [
        'seoTitleSuffix' => true,
        'seoTitleDefault' => 'My account',
        'defaultRobots' => 'noindex, nofollow',
    ])
    @include('partials.head-fonts')
    <link rel="stylesheet" href="{{ asset('css/jfa-website.css') }}?v=39">
    @stack('styles')
</head>
<body class="jfa-page">
    @include('partials.ui-extras')
    @include('partials.welcome-popup')
    @include('partials.jfa-header')

    @php
        $user = auth()->user();
        $initials = $user->initials();
        $avatarUrl = $user->avatarUrl();
        $accountRoute = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
        $navItems = [
            ['group' => 'Overview', 'items' => [
                ['label' => 'Dashboard', 'route' => 'account.dashboard', 'icon' => 'dashboard'],
            ]],
            ['group' => 'Bookings', 'items' => [
                ['label' => 'My Bookings', 'route' => 'account.bookings.index', 'icon' => 'luggage'],
                ['label' => 'Refund Tracking', 'route' => 'account.refunds.index', 'icon' => 'currency_rupee'],
            ]],
            ['group' => 'Preferences', 'items' => [
                ['label' => 'Saved Travellers', 'route' => 'account.saved-travellers.index', 'icon' => 'group'],
                ['label' => 'Wishlist', 'route' => 'account.wishlist.index', 'icon' => 'favorite'],
                ['label' => 'Offers & Discounts', 'route' => 'account.offers', 'icon' => 'local_offer'],
            ]],
            ['group' => 'Activity', 'items' => [
                ['label' => 'Notifications', 'route' => 'account.announcements.index', 'icon' => 'notifications'],
            ]],
            ['group' => 'Settings', 'items' => [
                ['label' => 'Profile', 'route' => 'account.profile.edit', 'icon' => 'person'],
                ['label' => 'Change Password', 'route' => 'account.password.edit', 'icon' => 'lock'],
            ]],
        ];
    @endphp

    <div class="jfa-account-page">
        <div class="jfa-container jfa-account-wrap">
            <aside class="jfa-account-sidebar">
                <div class="jfa-account-user">
                    <div class="jfa-account-user__row">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="" class="jfa-account-user__avatar jfa-account-user__avatar--photo">
                        @else
                            <span class="jfa-account-user__avatar">{{ $initials }}</span>
                        @endif
                        <span>
                            <strong style="display:block;font-size:14px;">{{ $user->name }}</strong>
                            <small style="font-size:12px;color:var(--jfa-muted);">{{ $user->email }}</small>
                        </span>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <a href="{{ route('home') }}" class="btn secondary" style="flex:1;padding:8px 10px;font-size:12px;">← Website</a>
                        <form method="post" action="{{ route('logout') }}" style="flex:1;margin:0;">
                            @csrf
                            <button type="submit" class="btn secondary" style="width:100%;padding:8px 10px;font-size:12px;color:#dc2626;border-color:#fecaca;">Log Out</button>
                        </form>
                    </div>
                </div>
                <nav class="jfa-account-nav" aria-label="Account navigation">
                    @foreach($navItems as $group)
                        @if(!$loop->first)<div class="jfa-account-nav__sep"></div>@endif
                        <div class="jfa-account-nav__group">{{ $group['group'] }}</div>
                        @foreach($group['items'] as $item)
                            <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) || request()->routeIs(str_replace('.edit', '.*', $item['route'])) || request()->routeIs(str_replace('.index', '.*', $item['route'])) || (str_contains($item['route'], 'bookings') && request()->routeIs('account.bookings.*')) ? 'is-active' : '' }}">
                                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    @endforeach
                </nav>
            </aside>

            <div class="jfa-account-main">
                <button type="button" class="jfa-account-mobile-toggle" id="jfa-acct-menu-toggle">
                    <span class="material-symbols-outlined">menu</span> Account Menu
                </button>
                <nav class="jfa-account-mobile-menu" id="jfa-acct-mobile-menu">
                    @foreach($navItems as $group)
                        @foreach($group['items'] as $item)
                            <a href="{{ route($item['route']) }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:14px;font-weight:600;">
                                <span class="material-symbols-outlined" style="font-size:18px;">{{ $item['icon'] }}</span>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    @endforeach
                </nav>

                <header class="jfa-account-head">
                    <nav class="jfa-breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                        <a href="{{ route('account.dashboard') }}">My Account</a>
                        @if(!request()->routeIs('account.dashboard'))
                            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                            <span aria-current="page">@yield('heading', 'Account')</span>
                        @endif
                    </nav>
                    <h1>@yield('heading', 'My account')</h1>
                </header>

                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.jfa-footer')
    @include('partials.tawk-to')
    @include('partials.jfa-floating')
    @stack('scripts')
    <script>
    (function () {
        var btn = document.getElementById('jfa-acct-menu-toggle');
        var menu = document.getElementById('jfa-acct-mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', function () { menu.classList.toggle('is-open'); });
        }
    })();
    </script>
    @include('partials.flash-swal', ['swalConfirmColor' => '#003B95'])
</body>
</html>
