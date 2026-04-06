@php
    $phone = $siteSetting?->support_phone ?? '+91 1800-000-0000';
    $email = $siteSetting?->support_email ?? 'support@jetflyairways.com';
    $topLeft = $siteSetting?->topstrip_left ?? __('jetfly.topstrip_left');
    $bName = $siteSetting?->brand_name ?? 'Jet Fly Airways';
    $bTag = $siteSetting?->brand_tagline ?? 'Book · Fly · Stay';
@endphp
<div class="mm-topbar" role="note">
    <div class="container mm-topbar-inner">
        <span class="mm-topbar-promo">{{ $topLeft }}</span>
        <div class="mm-topbar-actions">
            <span class="mm-topbar-locale locale-switch" aria-label="Language">
                <a href="{{ route('locale.switch', 'en') }}" class="mm-topbar-link {{ app()->getLocale() === 'en' ? 'is-active' : '' }}">English</a>
                <span class="mm-topbar-dot" aria-hidden="true"></span>
                <a href="{{ route('locale.switch', 'hi') }}" class="mm-topbar-link {{ app()->getLocale() === 'hi' ? 'is-active' : '' }}">हिंदी</a>
            </span>
            <button type="button" class="mm-topbar-link mm-topbar-btn" data-theme-toggle>{{ __('jetfly.theme_toggle') }}</button>
            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="mm-topbar-link mm-topbar-phone">{{ $phone }}</a>
            <a href="mailto:{{ $email }}" class="mm-topbar-link">{{ $email }}</a>
            @auth
                @if(($unreadAnnouncements ?? 0) > 0)
                    <a href="{{ route('account.announcements.index') }}" class="mm-topbar-link">Alerts<span class="notif-badge">{{ $unreadAnnouncements }}</span></a>
                @endif
            @endauth
        </div>
    </div>
</div>
<header class="mm-header">
    <div class="container mm-header-inner">
        <a href="{{ route('home') }}" class="mm-brand" aria-label="{{ $bName }} home">
            <span class="mm-brand-mark">{{ $bName }}</span>
            <span class="mm-brand-sub">{{ $bTag }}</span>
        </a>
        <nav class="mm-mainnav" aria-label="Main navigation">
            @php $headerMenu = $headerMenu ?? collect(); @endphp
            @forelse($headerMenu as $item)
                @if($item->children->isEmpty())
                    <a href="{{ $item->resolvedUrl() }}" class="mm-navlink {{ $item->isCurrent() ? 'is-active' : '' }}" @if($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $item->label }}</a>
                @else
                    <div class="mm-mega">
                        <button type="button" class="mm-navlink mm-mega-trigger" aria-haspopup="true" aria-expanded="false">{{ $item->label }} <span class="mm-caret" aria-hidden="true">▾</span></button>
                        <div class="mm-mega-panel" role="menu">
                            <div class="mm-mega-grid">
                                @foreach($item->children as $child)
                                    <a href="{{ $child->resolvedUrl() }}" role="menuitem" class="mm-mega-item {{ $child->isCurrent() ? 'is-active' : '' }}" @if($child->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $child->label }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <a href="{{ route('module.index', 'flights') }}" class="mm-navlink">{{ __('jetfly.nav_flights') }}</a>
                <a href="{{ route('module.index', 'hotels') }}" class="mm-navlink">{{ __('jetfly.nav_hotels') }}</a>
                <a href="{{ route('module.index', 'packages') }}" class="mm-navlink">{{ __('jetfly.nav_packages') }}</a>
                <div class="mm-mega">
                    <button type="button" class="mm-navlink mm-mega-trigger" aria-haspopup="true" aria-expanded="false">More <span class="mm-caret" aria-hidden="true">▾</span></button>
                    <div class="mm-mega-panel" role="menu">
                        <div class="mm-mega-grid">
                            <a href="{{ route('module.index', 'trains') }}" role="menuitem" class="mm-mega-item">Trains</a>
                            <a href="{{ route('module.index', 'buses') }}" role="menuitem" class="mm-mega-item">Buses</a>
                            <a href="{{ route('module.index', 'cabs') }}" role="menuitem" class="mm-mega-item">Cabs</a>
                            <a href="{{ route('module.index', 'visa') }}" role="menuitem" class="mm-mega-item">Visa</a>
                            <a href="{{ route('module.index', 'insurance') }}" role="menuitem" class="mm-mega-item">Insurance</a>
                            <a href="{{ route('refer-earn') }}" role="menuitem" class="mm-mega-item">Refer &amp; Earn</a>
                            <a href="{{ route('currency-converter') }}" role="menuitem" class="mm-mega-item">Currency Converter</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </nav>
        <div class="mm-header-cta">
            <a href="{{ route('home') }}#search" class="mm-btn mm-btn--primary">{{ __('jetfly.book_now') }}</a>
            @auth
                <details class="mm-account-dd">
                    <summary class="mm-btn mm-btn--outline mm-account-summary" aria-label="Account menu">
                        {{ \Illuminate\Support\Str::limit(auth()->user()->name, 18) }}
                        <span class="mm-caret" aria-hidden="true">▾</span>
                    </summary>
                    <div class="mm-account-panel" role="menu">
                        <a href="{{ route('account.bookings.index') }}" class="mm-account-link" role="menuitem">My Trips</a>
                        <a href="{{ route('account.saved-travellers.index') }}" class="mm-account-link" role="menuitem">Saved travellers</a>
                        <a href="{{ route('account.refunds.index') }}" class="mm-account-link" role="menuitem">Refund tracking</a>
                        <a href="{{ route('account.wishlist.index') }}" class="mm-account-link" role="menuitem">{{ __('jetfly.wishlist') }}</a>
                        @if(($unreadAnnouncements ?? 0) > 0)
                            <a href="{{ route('account.announcements.index') }}" class="mm-account-link" role="menuitem">{{ __('jetfly.notifications') }} <span class="notif-badge">{{ $unreadAnnouncements }}</span></a>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <div class="mm-account-sep" role="presentation"></div>
                            <a href="{{ route('admin.dashboard') }}" class="mm-account-link" role="menuitem">{{ __('jetfly.admin') }}</a>
                        @endif
                        <div class="mm-account-sep" role="presentation"></div>
                        <form method="post" action="{{ route('logout') }}" class="mm-account-logout-form">
                            @csrf
                            <button type="submit" class="mm-account-link--logout">{{ __('jetfly.log_out') }}</button>
                        </form>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}" class="mm-btn mm-btn--outline mm-login-wide">Login or Create Account <span class="mm-caret" aria-hidden="true">▾</span></a>
            @endauth
        </div>
        <button type="button" class="mm-nav-toggle" aria-expanded="false" aria-controls="mm-mobile-nav" data-mm-nav-toggle>
            <span class="mm-nav-toggle-bar"></span>
            <span class="mm-nav-toggle-bar"></span>
            <span class="mm-nav-toggle-bar"></span>
            <span class="visually-hidden">Menu</span>
        </button>
    </div>
    <div id="mm-mobile-nav" class="mm-mobile-drawer" hidden data-mm-drawer>
        <div class="mm-mobile-inner container">
            @forelse($headerMenu as $item)
                @if($item->children->isEmpty())
                    <a href="{{ $item->resolvedUrl() }}" class="mm-mobile-link">{{ $item->label }}</a>
                @else
                    <p class="mm-mobile-group">{{ $item->label }}</p>
                    @foreach($item->children as $child)
                        <a href="{{ $child->resolvedUrl() }}" class="mm-mobile-link mm-mobile-sublink">{{ $child->label }}</a>
                    @endforeach
                @endif
            @empty
                <a href="{{ route('module.index', 'flights') }}" class="mm-mobile-link">Flights</a>
                <a href="{{ route('module.index', 'hotels') }}" class="mm-mobile-link">Hotels</a>
                <a href="{{ route('module.index', 'packages') }}" class="mm-mobile-link">Holidays</a>
                <p class="mm-mobile-group">More</p>
                <a href="{{ route('module.index', 'trains') }}" class="mm-mobile-link mm-mobile-sublink">Trains</a>
                <a href="{{ route('module.index', 'buses') }}" class="mm-mobile-link mm-mobile-sublink">Buses</a>
                <a href="{{ route('module.index', 'cabs') }}" class="mm-mobile-link mm-mobile-sublink">Cabs</a>
                <a href="{{ route('module.index', 'visa') }}" class="mm-mobile-link mm-mobile-sublink">Visa</a>
                <a href="{{ route('module.index', 'insurance') }}" class="mm-mobile-link mm-mobile-sublink">Insurance</a>
                <a href="{{ route('refer-earn') }}" class="mm-mobile-link mm-mobile-sublink">Refer &amp; Earn</a>
                <a href="{{ route('currency-converter') }}" class="mm-mobile-link mm-mobile-sublink">Currency Converter</a>
            @endforelse
            @auth
                <p class="mm-mobile-group">Account</p>
                <a href="{{ route('account.bookings.index') }}" class="mm-mobile-link mm-mobile-sublink">My Trips</a>
                <a href="{{ route('account.saved-travellers.index') }}" class="mm-mobile-link mm-mobile-sublink">Saved travellers</a>
                <a href="{{ route('account.refunds.index') }}" class="mm-mobile-link mm-mobile-sublink">Refund tracking</a>
                <a href="{{ route('account.wishlist.index') }}" class="mm-mobile-link mm-mobile-sublink">{{ __('jetfly.wishlist') }}</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="mm-mobile-link mm-mobile-sublink">{{ __('jetfly.admin') }}</a>
                @endif
                <form method="post" action="{{ route('logout') }}" class="mm-mobile-logout-form">
                    @csrf
                    <button type="submit" class="mm-mobile-link mm-mobile-sublink" style="width:100%;text-align:left;border:none;background:none;font:inherit;cursor:pointer;">{{ __('jetfly.log_out') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mm-mobile-link mm-mobile-sublink">Login or Create Account</a>
            @endauth
            <a href="{{ route('home') }}#search" class="mm-btn mm-btn--primary mm-mobile-cta">{{ __('jetfly.book_now') }}</a>
        </div>
    </div>
</header>
<script>
(function () {
    var btn = document.querySelector('[data-mm-nav-toggle]');
    var drawer = document.querySelector('[data-mm-drawer]');
    if (btn && drawer) {
        btn.addEventListener('click', function () {
            var open = drawer.hasAttribute('hidden');
            drawer.toggleAttribute('hidden', !open);
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    function closeAllMegas() {
        document.querySelectorAll('.mm-mega.is-open').forEach(function (mega) {
            mega.classList.remove('is-open');
            var tr = mega.querySelector('.mm-mega-trigger');
            if (tr) tr.setAttribute('aria-expanded', 'false');
        });
    }

    document.querySelectorAll('.mm-mega-trigger').forEach(function (trigger) {
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var mega = trigger.closest('.mm-mega');
            if (!mega) return;
            var isOpen = mega.classList.contains('is-open');
            if (isOpen) {
                mega.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            } else {
                closeAllMegas();
                mega.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });

    document.querySelectorAll('.mm-account-dd').forEach(function (dd) {
        dd.addEventListener('toggle', function () {
            if (!dd.open) return;
            document.querySelectorAll('.mm-account-dd').forEach(function (other) {
                if (other !== dd) other.removeAttribute('open');
            });
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.mm-account-dd')) {
            document.querySelectorAll('.mm-account-dd').forEach(function (dd) { dd.removeAttribute('open'); });
        }
        if (!e.target.closest('.mm-mega')) {
            closeAllMegas();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllMegas();
        }
    });
})();
</script>
