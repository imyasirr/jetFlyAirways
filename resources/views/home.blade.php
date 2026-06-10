@extends('layouts.app')

@section('body_class', 'page-home')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2">
@endpush

@section('title', $siteSeo?->meta_title ?? 'Home - Jet Fly Airways')

@section('meta_description', $siteSeo?->meta_description ?? 'Jet Fly Airways - search flights, hotels, buses, trains, cabs and holiday packages with live inventory.')

@section('full')
@php
    $fallbackHero = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1800&auto=format&fit=crop';
    $heroSlides = collect($banners ?? [])
        ->filter(fn ($banner) => $banner->is_active)
        ->map(function ($banner) {
            return [
                'image' => \App\Support\PublicImageStorage::url($banner->image),
                'title' => $banner->title,
                'description' => $banner->description,
                'link' => $banner->link,
                'button_text' => $banner->button_text,
            ];
        })
        ->filter(fn ($slide) => filled($slide['image']))
        ->values();

    if ($heroSlides->isEmpty()) {
        $heroSlides = collect([[
            'image' => $fallbackHero,
            'title' => 'Discover your dream vacation',
            'description' => 'Explore luxury hotels, flights, tours and unforgettable travel experiences with Jet Fly Airways.',
            'link' => '#search',
            'button_text' => 'Explore now',
        ]]);
    }
@endphp
<section class="home-premium-hero" aria-label="Book travel" data-home-hero-carousel>
    <div class="home-premium-hero__track">
        @foreach($heroSlides as $slide)
            <article class="home-premium-hero__slide {{ $loop->first ? 'is-active' : '' }}" style="--home-hero-slide: url('{{ e($slide['image']) }}');" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                <div class="home-premium-hero__bg" aria-hidden="true"></div>
                <div class="container home-premium-hero__inner home-premium-hero__inner--single">
                    <div class="home-premium-hero__copy">
                        <p class="home-ota-kicker">Best fares - easy booking - 24x7 support</p>
                        <h1 class="home-ota-title">{{ $slide['title'] ?: 'Discover your dream vacation' }}</h1>
                        <p class="home-ota-sub">{{ $slide['description'] ?: 'Explore luxury hotels, flights, tours and unforgettable travel experiences with Jet Fly Airways.' }}</p>
                        <div class="home-premium-actions">
                            <a href="{{ $slide['link'] ?: '#search' }}" class="home-premium-btn home-premium-btn--gold">{{ $slide['button_text'] ?: 'Explore now' }}</a>
                            <a href="{{ route('module.index', 'packages') }}" class="home-premium-btn home-premium-btn--glass">View holidays</a>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
    @if($heroSlides->count() > 1)
        <div class="home-premium-hero__dots" aria-label="Banner slides">
            @foreach($heroSlides as $slide)
                <button type="button" class="home-premium-hero__dot {{ $loop->first ? 'is-active' : '' }}" data-home-hero-dot="{{ $loop->index }}" aria-label="Show banner {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
            @endforeach
        </div>
    @endif
</section>

<div class="container home-search-wrapper">
    @include('partials.home-search-panel')
</div>

<div class="container home-premium-trust-wrap">
    <div class="home-ota-trust" role="list" aria-label="Why book with Jet Fly Airways">
        <span class="home-ota-trust-item" role="listitem"><strong>Best fares</strong> on routes you choose</span>
        <span class="home-ota-trust-item" role="listitem"><strong>Flexible search</strong> across hotels and packages</span>
        <span class="home-ota-trust-item" role="listitem"><strong>Secure checkout</strong> ready for UPI and cards</span>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    var root = document.querySelector('[data-home-hero-carousel]');
    if (!root) return;
    var slides = root.querySelectorAll('.home-premium-hero__slide');
    var dots = root.querySelectorAll('.home-premium-hero__dot');
    if (slides.length < 2) return;
    var current = 0;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var timer = null;

    function show(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach(function (slide, i) {
            var active = i === current;
            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', active ? 'false' : 'true');
        });
        dots.forEach(function (dot, i) {
            var active = i === current;
            dot.classList.toggle('is-active', active);
            dot.setAttribute('aria-current', active ? 'true' : 'false');
        });
    }

    function start() {
        if (reduceMotion) return;
        stop();
        timer = setInterval(function () { show(current + 1); }, 5500);
    }

    function stop() {
        if (timer) clearInterval(timer);
        timer = null;
    }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            stop();
            show(parseInt(dot.getAttribute('data-home-hero-dot'), 10) || 0);
            start();
        });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) stop();
        else start();
    });
    start();
})();
</script>
@endpush

@section('content')
@include('partials.home-featured-sections')
@endsection
