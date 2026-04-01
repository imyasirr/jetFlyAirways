<div class="topstrip" role="note">
    <div class="container topstrip-inner">
        <span class="topstrip-left">{{ __('jetfly.topstrip_left') }}</span>
        <span class="topstrip-right">
            <span class="locale-switch" aria-label="Language">
                <a href="{{ route('locale.switch', 'en') }}" class="topstrip-link {{ app()->getLocale() === 'en' ? 'is-active' : '' }}">EN</a>
                <span class="topstrip-sep" aria-hidden="true">·</span>
                <a href="{{ route('locale.switch', 'hi') }}" class="topstrip-link {{ app()->getLocale() === 'hi' ? 'is-active' : '' }}">HI</a>
            </span>
            <span class="topstrip-sep" aria-hidden="true">|</span>
            <button type="button" class="theme-toggle" data-theme-toggle>{{ __('jetfly.theme_toggle') }}</button>
            <span class="topstrip-sep" aria-hidden="true">|</span>
            <a href="tel:+9118000000000" class="topstrip-link">+91 1800-000-0000</a>
            <span class="topstrip-sep" aria-hidden="true">|</span>
            <a href="mailto:support@jetflyairways.com" class="topstrip-link">support@jetflyairways.com</a>
            @auth
                @if(($unreadAnnouncements ?? 0) > 0)
                    <span class="topstrip-sep" aria-hidden="true">|</span>
                    <a href="{{ route('account.announcements.index') }}" class="topstrip-link">Alerts<span class="notif-badge">{{ $unreadAnnouncements }}</span></a>
                @endif
            @endauth
        </span>
    </div>
</div>
<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand-block">
            <span class="brand">Jet Fly Airways</span>
            <span class="brand-tagline">Book · Fly · Stay</span>
        </a>
        <nav class="mega-nav" aria-label="Main navigation">
            @php $headerMenu = $headerMenu ?? collect(); @endphp
            @forelse($headerMenu as $item)
                @if($item->children->isEmpty())
                    <a href="{{ $item->resolvedUrl() }}" class="mega-link {{ $item->isCurrent() ? 'is-active' : '' }}" @if($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $item->label }}</a>
                @else
                    <div class="mega-wrap">
                        <button type="button" class="mega-trigger" aria-haspopup="true" aria-expanded="false">{{ $item->label }} <span class="mega-caret" aria-hidden="true">▾</span></button>
                        <div class="mega-panel" role="menu">
                            <div class="mega-cols">
                                @foreach($item->children as $child)
                                    <a href="{{ $child->resolvedUrl() }}" role="menuitem" class="{{ $child->isCurrent() ? 'is-active' : '' }}" @if($child->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $child->label }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <a href="{{ route('home') }}" class="mega-link {{ request()->routeIs('home') ? 'is-active' : '' }}">{{ __('jetfly.nav_home') }}</a>
                <a href="{{ route('welcome') }}" class="mega-link {{ request()->routeIs('welcome') ? 'is-active' : '' }}">{{ __('jetfly.nav_discover') }}</a>
                <a href="{{ route('module.index', 'flights') }}" class="mega-link">{{ __('jetfly.nav_flights') }}</a>
                <a href="{{ route('module.index', 'hotels') }}" class="mega-link">{{ __('jetfly.nav_hotels') }}</a>
                <a href="{{ route('module.index', 'packages') }}" class="mega-link">{{ __('jetfly.nav_packages') }}</a>
            @endforelse
        </nav>
        <div class="header-actions">
            <a href="{{ route('home') }}#search" class="btn btn-header">{{ __('jetfly.book_now') }}</a>
            @auth
                <a href="{{ route('account.wishlist.index') }}" class="btn btn-header-secondary">{{ __('jetfly.wishlist') }}</a>
                <a href="{{ route('account.announcements.index') }}" class="btn btn-header-secondary">{{ __('jetfly.notifications') }}
                    @if(($unreadAnnouncements ?? 0) > 0)
                        <span class="notif-badge">{{ $unreadAnnouncements }}</span>
                    @endif
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-header-secondary">{{ __('jetfly.admin') }}</a>
                @endif
                <form method="post" action="{{ route('logout') }}" style="margin:0;display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-header-secondary" style="cursor:pointer;">{{ __('jetfly.log_out') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-header-secondary">{{ __('jetfly.sign_in') }}</a>
                <a href="{{ route('register') }}" class="btn btn-header-secondary">{{ __('jetfly.register') }}</a>
            @endauth
        </div>
    </div>
</header>
