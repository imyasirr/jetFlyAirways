@php
    $phone = $siteSetting?->support_phone ?? '+91 1800-000-0000';
    $email = $siteSetting?->support_email ?? 'support@jetflyairways.com';
    $topLeft = $siteSetting?->topstrip_left ?? __('jetfly.topstrip_left');
    $bName = $siteSetting?->brand_name ?? 'Jet Fly Airways';
    $bTag = $siteSetting?->brand_tagline ?? 'Book · Fly · Stay';
    $logoUrl = ($siteSetting ?? null)?->logo_image
        ? \App\Support\PublicImageStorage::url($siteSetting->logo_image)
        : null;
    $user = auth()->user();
    $initials = $user
        ? collect(explode(' ', (string) $user->name))->filter()->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('') ?: 'U'
        : '';
@endphp

<div class="jfa-promo" id="jfa-promo" role="note">
    <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;margin-right:4px;">local_offer</span>
    {{ $topLeft }}
    <button type="button" class="jfa-promo__close" id="jfa-promo-close" aria-label="Dismiss">
        <span class="material-symbols-outlined" style="font-size:18px;">close</span>
    </button>
</div>

<header class="jfa-header">
    <div class="jfa-container jfa-header__inner">
        <a href="{{ route('home') }}" class="jfa-brand" aria-label="{{ $bName }} home">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $bName }}" class="jfa-brand__logo">
            @else
                <span class="jfa-brand__icon"><span class="material-symbols-outlined filled">flight</span></span>
            @endif
            <span>
                <span class="jfa-brand__name">{{ $bName }}</span>
                @if($bTag)
                    <span class="jfa-brand__tag">{{ $bTag }}</span>
                @endif
            </span>
        </a>

        <nav class="jfa-nav" aria-label="Main navigation">
            @php $headerMenu = $headerMenu ?? collect(); @endphp
            @forelse($headerMenu as $item)
                @if($item->children->isEmpty())
                    <a href="{{ $item->resolvedUrl() }}" class="jfa-navlink {{ $item->isCurrent() ? 'is-active' : '' }}" @if($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $item->label }}</a>
                @else
                    <div class="jfa-dropdown" data-jfa-dropdown>
                        <button type="button" class="jfa-navlink" aria-haspopup="true" aria-expanded="false">{{ $item->label }} <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">expand_more</span></button>
                        <div class="jfa-dropdown__panel">
                            @foreach($item->children as $child)
                                @if($child->children->isEmpty())
                                    <a href="{{ $child->resolvedUrl() }}" class="jfa-dropdown__item" @if($child->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $child->label }}</a>
                                @else
                                    <div style="padding:8px 16px 4px;font-size:11px;font-weight:700;text-transform:uppercase;color:var(--jfa-muted);">{{ $child->label }}</div>
                                    @foreach($child->children as $grand)
                                        <a href="{{ $grand->resolvedUrl() }}" class="jfa-dropdown__item" @if($grand->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $grand->label }}</a>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @empty
                <a href="{{ route('module.index', 'flights') }}" class="jfa-navlink">{{ __('jetfly.nav_flights') }}</a>
                <a href="{{ route('module.index', 'hotels') }}" class="jfa-navlink">{{ __('jetfly.nav_hotels') }}</a>
                <a href="{{ route('module.index', 'packages') }}" class="jfa-navlink">{{ __('jetfly.nav_packages') }}</a>
                <a href="{{ route('module.index', 'trains') }}" class="jfa-navlink">Trains</a>
                <a href="{{ route('module.index', 'buses') }}" class="jfa-navlink">Buses</a>
                <div class="jfa-dropdown" data-jfa-dropdown>
                    <button type="button" class="jfa-navlink" aria-haspopup="true">More <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">expand_more</span></button>
                    <div class="jfa-dropdown__panel">
                        <a href="{{ route('module.index', 'cabs') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">local_taxi</span> Cabs</a>
                        <a href="{{ route('module.index', 'visa') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">travel_explore</span> Visa</a>
                        <a href="{{ route('module.index', 'insurance') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">shield</span> Insurance</a>
                        <a href="{{ route('refer-earn') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">redeem</span> Refer &amp; Earn</a>
                        <a href="{{ route('currency-converter') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">currency_exchange</span> Currency Converter</a>
                    </div>
                </div>
            @endforelse
        </nav>

        <div class="jfa-header__actions">
            @auth
                @if(($unreadAnnouncements ?? 0) > 0)
                    <a href="{{ route('account.announcements.index') }}" class="jfa-notif-btn" aria-label="Notifications">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="jfa-notif-dot"></span>
                    </a>
                @endif
                <div class="jfa-dropdown jfa-dropdown--account" data-jfa-dropdown>
                    <button type="button" class="jfa-account-btn" aria-haspopup="true">
                        <span class="jfa-avatar">{{ $initials }}</span>
                        {{ \Illuminate\Support\Str::limit($user->name, 16) }}
                        <span class="material-symbols-outlined" style="font-size:18px;">expand_more</span>
                    </button>
                    <div class="jfa-dropdown__panel jfa-dropdown__panel--right">
                        <div class="jfa-dropdown__head">
                            <strong>{{ $user->name }}</strong>
                            <small>{{ $user->email }}</small>
                        </div>
                        <a href="{{ route('account.bookings.index') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">luggage</span> My Trips</a>
                        <a href="{{ route('account.saved-travellers.index') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">group</span> Saved Travellers</a>
                        <a href="{{ route('account.refunds.index') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">currency_rupee</span> Refunds</a>
                        <a href="{{ route('account.wishlist.index') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">favorite</span> Wishlist</a>
                        <a href="{{ route('account.announcements.index') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">notifications</span> Notifications</a>
                        <a href="{{ route('account.profile.edit') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">person</span> Profile</a>
                        @if($user->isAdmin())
                            <div class="jfa-dropdown__sep"></div>
                            <a href="{{ route('admin.dashboard') }}" class="jfa-dropdown__item"><span class="material-symbols-outlined">admin_panel_settings</span> Admin</a>
                        @endif
                        <div class="jfa-dropdown__sep"></div>
                        <form method="post" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="jfa-dropdown__item jfa-dropdown__logout"><span class="material-symbols-outlined">logout</span> Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="jfa-btn-signin">Sign In</a>
                <a href="{{ route('register') }}" class="jfa-btn-register">Register</a>
            @endauth
        </div>

        <button type="button" class="jfa-hamburger" id="jfa-hamburger" aria-label="Menu" aria-expanded="false">
            <span class="material-symbols-outlined" id="jfa-hamburger-icon">menu</span>
        </button>
    </div>

    <nav class="jfa-mobile-nav" id="jfa-mobile-nav" aria-label="Mobile navigation">
        @forelse($headerMenu as $item)
            @if($item->children->isEmpty())
                <a href="{{ $item->resolvedUrl() }}">{{ $item->label }}</a>
            @else
                <p class="jfa-mobile-nav__group">{{ $item->label }}</p>
                @foreach($item->children as $child)
                    <a href="{{ $child->resolvedUrl() }}">{{ $child->label }}</a>
                @endforeach
            @endif
        @empty
            <a href="{{ route('module.index', 'flights') }}">Flights</a>
            <a href="{{ route('module.index', 'hotels') }}">Hotels</a>
            <a href="{{ route('module.index', 'packages') }}">Packages</a>
            <a href="{{ route('module.index', 'trains') }}">Trains</a>
            <a href="{{ route('module.index', 'buses') }}">Buses</a>
            <p class="jfa-mobile-nav__group">More</p>
            <a href="{{ route('module.index', 'cabs') }}">Cabs</a>
            <a href="{{ route('module.index', 'visa') }}">Visa</a>
            <a href="{{ route('module.index', 'insurance') }}">Insurance</a>
            <a href="{{ route('refer-earn') }}">Refer &amp; Earn</a>
            <a href="{{ route('currency-converter') }}">Currency Converter</a>
        @endforelse
        <div class="jfa-mobile-nav__cta">
            @auth
                <a href="{{ route('account.dashboard') }}" style="background:var(--jfa-promo-yellow);color:var(--jfa-on-yellow);font-weight:700;">My Account</a>
            @else
                <a href="{{ route('login') }}" style="border:1px solid rgba(255,255,255,.35);color:#fff;">Sign In</a>
                <a href="{{ route('register') }}" style="background:var(--jfa-promo-yellow);color:var(--jfa-on-yellow);font-weight:700;">Register</a>
            @endauth
        </div>
    </nav>
</header>

<script>
(function () {
    var promo = document.getElementById('jfa-promo');
    var promoClose = document.getElementById('jfa-promo-close');
    if (promo && promoClose) {
        try {
            if (sessionStorage.getItem('jfa_promo_dismissed')) promo.classList.add('is-hidden');
        } catch (e) {}
        promoClose.addEventListener('click', function () {
            promo.classList.add('is-hidden');
            try { sessionStorage.setItem('jfa_promo_dismissed', '1'); } catch (e) {}
        });
    }

    document.querySelectorAll('[data-jfa-dropdown]').forEach(function (wrap) {
        var btn = wrap.querySelector('button');
        if (!btn) return;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = wrap.classList.contains('is-open');
            document.querySelectorAll('[data-jfa-dropdown].is-open').forEach(function (el) { el.classList.remove('is-open'); });
            if (!open) wrap.classList.add('is-open');
        });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('[data-jfa-dropdown].is-open').forEach(function (el) { el.classList.remove('is-open'); });
    });

    var ham = document.getElementById('jfa-hamburger');
    var mob = document.getElementById('jfa-mobile-nav');
    var icon = document.getElementById('jfa-hamburger-icon');
    if (ham && mob) {
        ham.addEventListener('click', function () {
            var open = mob.classList.toggle('is-open');
            ham.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (icon) icon.textContent = open ? 'close' : 'menu';
        });
    }
})();
</script>
