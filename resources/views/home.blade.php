@extends('layouts.app')

@section('body_class', 'page-home')

@section('title', $siteSeo?->meta_title ?? 'Home - Jet Fly Airways')

@section('meta_description', $siteSeo?->meta_description ?? 'Jet Fly Airways - search flights, hotels, buses, trains, cabs and holiday packages with live inventory.')

@section('full')
    @php
        $heroSetting = $siteSetting ?? \App\Models\SiteSetting::query()->first();
        $settingHero = $heroSetting?->hero_image
            ? \App\Support\PublicImageStorage::url($heroSetting->hero_image)
            : null;
        $fallbackHero = $settingHero ?: 'https://images.unsplash.com/photo-1436491865332-7a61a1092e56?auto=format&fit=crop&q=80&w=2000';
        $heroSlides = collect($banners ?? [])
            ->filter(fn ($banner) => $banner->is_active)
            ->map(fn ($banner) => [
                'image' => \App\Support\PublicImageStorage::url($banner->image),
                'title' => $banner->title ?: 'Fly Beyond Horizons',
                'description' => $banner->description ?: 'Discover India and the world with unbeatable fares on 500+ routes.',
                'link' => $banner->link ?: route('module.index', 'flights'),
                'button_text' => $banner->button_text ?: 'Search Flights',
                'show_button' => $banner->show_button ?? true,
                'tags' => $banner->tagList(),
                'show_tags' => $banner->show_tags ?? true,
            ])
            ->filter(fn ($slide) => filled($slide['image']))
            ->values();
        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([[
                'image' => $fallbackHero,
                'title' => 'Fly Beyond Horizons',
                'description' => 'Discover India and the world with unbeatable fares on 500+ routes.',
                'link' => route('module.index', 'flights'),
                'button_text' => 'Search Flights',
                'show_button' => true,
                'tags' => ['✈ Flights from ₹999'],
                'show_tags' => true,
            ]]);
        }
        $services = [
            ['icon' => 'flight', 'label' => 'Flights', 'href' => route('module.index', 'flights'), 'color' => '#003B95'],
            ['icon' => 'hotel', 'label' => 'Hotels', 'href' => route('module.index', 'hotels'), 'color' => '#0d9488'],
            ['icon' => 'beach_access', 'label' => 'Packages', 'href' => route('module.index', 'packages'), 'color' => '#f97316'],
            ['icon' => 'train', 'label' => 'Trains', 'href' => route('module.index', 'trains'), 'color' => '#7c3aed'],
            ['icon' => 'directions_bus', 'label' => 'Buses', 'href' => route('module.index', 'buses'), 'color' => '#b45309'],
            ['icon' => 'local_taxi', 'label' => 'Cabs', 'href' => route('module.index', 'cabs'), 'color' => '#0369a1'],
            ['icon' => 'travel_explore', 'label' => 'Visa', 'href' => route('module.index', 'visa'), 'color' => '#be185d'],
            ['icon' => 'shield', 'label' => 'Insurance', 'href' => route('module.index', 'insurance'), 'color' => '#047857'],
        ];
        $trustBadges = [
            ['icon' => 'verified_user', 'title' => 'Secure Payments', 'subtitle' => 'PCI-DSS compliant. UPI, Cards & Netbanking.'],
            ['icon' => 'support_agent', 'title' => '24/7 Support', 'subtitle' => 'Dedicated team, 365 days a year.'],
            ['icon' => 'price_check', 'title' => 'Best Price Guarantee', 'subtitle' => "Found it cheaper? We'll match it."],
            ['icon' => 'workspace_premium', 'title' => 'Award-Winning Service', 'subtitle' => 'Trusted by millions of travellers.'],
        ];
    @endphp

    <section class="jfa-hero" data-home-hero-carousel aria-label="Book travel">
        <div class="jfa-hero__bg" aria-hidden="true">
            @foreach($heroSlides as $slide)
                <img src="{{ e($slide['image']) }}" alt="" class="{{ $loop->first ? 'is-active' : '' }}" data-hero-slide @if(!$loop->first) style="opacity:0;" @endif>
            @endforeach
        </div>
        <div class="jfa-hero__overlay" aria-hidden="true"></div>
        <div class="jfa-container jfa-hero__content">
            @foreach($heroSlides as $slide)
                <div class="jfa-hero__copy {{ $loop->first ? 'is-active' : '' }}" data-hero-copy @if(!$loop->first) hidden @endif>
                    @if(($slide['show_tags'] ?? true) && ! empty($slide['tags']))
                        <div class="jfa-hero__tags">
                            @foreach($slide['tags'] as $tag)
                                <span class="jfa-hero__tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                    <h1>{{ $slide['title'] }}</h1>
                    <p class="jfa-hero__sub">{{ $slide['description'] }}</p>
                    @if($slide['show_button'] ?? true)
                        <a href="{{ $slide['link'] ?: '#search' }}" class="jfa-hero__cta">
                            {{ $slide['button_text'] ?: 'Explore now' }}
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    @endif
                </div>
            @endforeach
            @if($heroSlides->count() > 1)
                <div class="jfa-hero__dots" aria-label="Banner slides">
                    @foreach($heroSlides as $slide)
                        <button type="button" class="jfa-hero__dot {{ $loop->first ? 'is-active' : '' }}" data-hero-dot="{{ $loop->index }}" aria-label="Slide {{ $loop->iteration }}" style="width:{{ $loop->first ? '32px' : '8px' }};"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <div class="jfa-container jfa-hero__search-wrap" id="search">
        @include('partials.home-search-panel')
    </div>

    <section class="jfa-section jfa-section--white">
        <div class="jfa-container">
            <div class="jfa-grid jfa-grid--4">
            @foreach($trustBadges as $badge)
                <div class="jfa-trust-card">
                    <span class="jfa-trust-card__icon"><span class="material-symbols-outlined filled">{{ $badge['icon'] }}</span></span>
                    <span>
                        <p class="jfa-trust-card__title">{{ $badge['title'] }}</p>
                        <p class="jfa-trust-card__sub">{{ $badge['subtitle'] }}</p>
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    </section>

    <section class="jfa-section jfa-section--muted">
        <div class="jfa-container">
            <div style="text-align:center;margin-bottom:32px;">
                <h2 class="jfa-section-title">All Travel Services</h2>
                <p class="jfa-section-sub">Everything you need for seamless travel — in one place.</p>
            </div>
            <div class="jfa-services-row">
                @foreach($services as $s)
                    <a href="{{ $s['href'] }}" class="jfa-service">
                        <span class="jfa-service__icon" style="background:{{ $s['color'] }}15;border:1.5px solid {{ $s['color'] }}30;">
                            <span class="material-symbols-outlined filled" style="color:{{ $s['color'] }};font-size:28px;">{{ $s['icon'] }}</span>
                        </span>
                        <span class="jfa-service__label">{{ $s['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.home-featured-sections')

    <section class="jfa-section jfa-section--white" style="padding-top:0;">
        <div class="jfa-container">
            <div class="jfa-newsletter-banner">
                <div class="jfa-newsletter-banner__copy">
                    <h2>Get Deals in Your Inbox</h2>
                    <p>Subscribe to our newsletter and never miss a flash sale.</p>
                    <form class="jfa-newsletter-banner__form" action="{{ route('contact.create') }}" method="get">
                        <input type="email" name="email" placeholder="your@email.com" required aria-label="Email">
                        <button type="submit">Subscribe Now</button>
                    </form>
                    <p style="font-size:12px;color:rgba(191,219,254,.8);margin:12px 0 0;">No spam. Unsubscribe anytime.</p>
                </div>
                <div class="jfa-newsletter-banner__img">
                    <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&q=80&w=600" alt="" loading="lazy">
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    {{-- Featured sections include their own jfa-section + jfa-container --}}
@endsection

@push('styles')
<style>.page-home main > .jfa-container:empty { display: none; }</style>
@endpush

@push('scripts')
<script>
(function () {
    var root = document.querySelector('[data-home-hero-carousel]');
    if (!root) return;
    var slides = root.querySelectorAll('[data-hero-slide]');
    var copies = root.querySelectorAll('[data-hero-copy]');
    var dots = root.querySelectorAll('[data-hero-dot]');
    var current = 0;
    var timer = null;

    function show(i) {
        current = (i + slides.length) % slides.length;
        slides.forEach(function (el, idx) {
            var active = idx === current;
            el.classList.toggle('is-active', active);
            el.style.opacity = active ? '1' : '0';
        });
        copies.forEach(function (el, idx) {
            el.hidden = idx !== current;
            el.classList.toggle('is-active', idx === current);
        });
        dots.forEach(function (dot, idx) {
            dot.classList.toggle('is-active', idx === current);
            dot.style.width = idx === current ? '32px' : '8px';
        });
    }

    if (slides.length < 2) return;
    function start() { timer = setInterval(function () { show(current + 1); }, 5000); }
    function stop() { if (timer) clearInterval(timer); timer = null; }
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () { stop(); show(parseInt(dot.getAttribute('data-hero-dot'), 10) || 0); start(); });
    });
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    start();
})();
</script>
@endpush
