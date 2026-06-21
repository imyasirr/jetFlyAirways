@extends('layouts.app')

@section('body_class', 'page-discover')

@section('title', $siteSeo?->meta_title ?? 'Discover — Jet Fly Airways')

@section('meta_description', $siteSeo?->meta_description ?? 'Explore Jet Fly Airways — flights, hotels, packages and ground transport with live inventory and secure booking.')

@section('full')
    @php
        $heroSetting = $siteSetting ?? \App\Models\SiteSetting::query()->first();
        $heroImage = $heroSetting?->hero_image
            ? \App\Support\PublicImageStorage::url($heroSetting->hero_image)
            : 'https://images.unsplash.com/photo-1436491865332-7a61a1092e56?auto=format&fit=crop&q=80&w=2000';
        $discoverLinks = [
            ['icon' => 'info', 'label' => 'About us', 'href' => route('pages.show', 'about'), 'desc' => 'Our mission and platform'],
            ['icon' => 'work', 'label' => 'Open roles', 'href' => route('jobs.index'), 'desc' => 'Apply for current vacancies'],
            ['icon' => 'groups', 'label' => 'Careers', 'href' => route('pages.show', 'careers'), 'desc' => 'Life at Jet Fly'],
            ['icon' => 'article', 'label' => 'Travel blog', 'href' => route('blog.index'), 'desc' => 'Tips and inspiration'],
        ];
    @endphp

    @include('partials.jfa-page-hero', [
        'title' => 'Discover Jet Fly',
        'description' => 'Enterprise travel stack with live inventory — search flights, stays, packages and ground transport in one place.',
        'icon' => 'travel_explore',
        'accentColor' => '#003B95',
        'bannerImage' => $heroImage,
        'heroClass' => 'jfa-cms-hero jfa-discover-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Discover'],
        ],
    ])

    <div class="jfa-container jfa-discover-search-wrap" id="search">
        @include('partials.home-search-panel')
    </div>
@endsection

@section('content')
    <section class="jfa-discover-stats" aria-label="Platform statistics">
        <div class="jfa-discover-stats__grid">
            <div class="jfa-discover-stat">
                <strong>{{ number_format($stats['flights']) }}</strong>
                <span>Active flights</span>
            </div>
            <div class="jfa-discover-stat">
                <strong>{{ number_format($stats['hotels']) }}</strong>
                <span>Hotels</span>
            </div>
            <div class="jfa-discover-stat">
                <strong>{{ number_format($stats['packages']) }}</strong>
                <span>Packages</span>
            </div>
            <div class="jfa-discover-stat">
                <strong>{{ number_format($stats['bookings']) }}</strong>
                <span>Bookings</span>
            </div>
            <div class="jfa-discover-stat">
                <strong>{{ number_format($stats['buses'] + $stats['trains'] + $stats['cabs']) }}</strong>
                <span>Ground routes</span>
            </div>
        </div>
    </section>

    <section class="jfa-discover-links" aria-label="Explore more">
        <h2 class="jfa-section-title">Explore more</h2>
        <div class="jfa-discover-links__grid">
            @foreach($discoverLinks as $link)
                <a href="{{ $link['href'] }}" class="jfa-discover-link-card">
                    <span class="jfa-discover-link-card__icon"><span class="material-symbols-outlined filled">{{ $link['icon'] }}</span></span>
                    <span class="jfa-discover-link-card__body">
                        <strong>{{ $link['label'] }}</strong>
                        <span>{{ $link['desc'] }}</span>
                    </span>
                    <span class="material-symbols-outlined jfa-discover-link-card__arrow">arrow_forward</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="jfa-discover-benefits">
        <h2 class="jfa-section-title">Why travellers choose us</h2>
        <div class="jfa-grid jfa-grid--3">
            <div class="jfa-discover-benefit">
                <span class="material-symbols-outlined filled">hub</span>
                <h3>Unified operations</h3>
                <p>Flights, hotels, packages, buses, trains and cabs — managed from one platform with role-based admin access.</p>
            </div>
            <div class="jfa-discover-benefit">
                <span class="material-symbols-outlined filled">database</span>
                <h3>Live inventory</h3>
                <p>Featured sections and listings update from your catalogue — real data, not static placeholders.</p>
            </div>
            <div class="jfa-discover-benefit">
                <span class="material-symbols-outlined filled">trending_up</span>
                <h3>Built to scale</h3>
                <p>Structured for payments, GST-compliant invoicing, and future B2B or corporate travel modules.</p>
            </div>
        </div>
    </section>

    @include('partials.home-featured-sections')
@endsection
