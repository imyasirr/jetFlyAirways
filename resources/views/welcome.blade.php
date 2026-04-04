@extends('layouts.app')

@section('body_class', 'page-home')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2">
<link rel="stylesheet" href="{{ asset('css/welcome.css') }}?v=1">
@endpush

@section('title', $siteSeo?->meta_title ?? 'Discover — Jet Fly Airways')

@section('meta_description', $siteSeo?->meta_description ?? 'Jet Fly Airways enterprise travel platform — flights, hotels, packages, buses, trains and cabs with live inventory.')

@section('full')
@php
    $heroImgUrl = ($siteSetting ?? null)?->hero_image
        ? \App\Support\PublicImageStorage::url($siteSetting->hero_image)
        : null;
@endphp
<section class="home-ota home-ota--hero home-ota--enterprise {{ $heroImgUrl ? 'home-ota--has-photo' : '' }}" aria-label="Platform overview">
    <div
        class="home-ota-bg {{ $heroImgUrl ? 'home-ota-bg--photo' : '' }}"
        aria-hidden="true"
        @if($heroImgUrl) style="background-image:url('{{ e($heroImgUrl) }}')" @endif
    ></div>
    @if($heroImgUrl)
        <div class="home-ota-scrim" aria-hidden="true"></div>
    @endif
    <div class="container home-ota-inner">
        <header class="home-ota-headline">
            <p class="home-ota-kicker">Enterprise travel stack · Live inventory</p>
            <h1 class="home-ota-title">Where every journey meets reliability</h1>
            <p class="home-ota-sub">Flights, hotels, packages, and ground transport — backed by admin tools, secure booking, and real-time listings.</p>
        </header>
        @include('partials.home-search-panel')
    </div>
</section>

<section class="hero hero-welcome">
    <div class="container">
        <span class="pill">Enterprise-grade travel platform · Live inventory</span>
        <h1>Where every journey meets reliability</h1>
        <p class="lead">Jet Fly Airways connects travellers with verified flights, hotels, packages, and ground transport — backed by a full admin stack, secure booking flow, and real-time listings from your database.</p>
        <div class="stats-strip" aria-label="Live platform statistics">
            <div class="stat-item"><strong>{{ number_format($stats['flights']) }}</strong><span>Active flights</span></div>
            <div class="stat-item"><strong>{{ number_format($stats['hotels']) }}</strong><span>Hotels</span></div>
            <div class="stat-item"><strong>{{ number_format($stats['packages']) }}</strong><span>Packages</span></div>
            <div class="stat-item"><strong>{{ number_format($stats['bookings']) }}</strong><span>Bookings</span></div>
            <div class="stat-item"><strong>{{ number_format($stats['buses'] + $stats['trains'] + $stats['cabs']) }}</strong><span>Ground routes</span></div>
        </div>
    </div>
</section>

@include('partials.home-banner-slider')
@endsection

@section('content')
<div class="why-grid">
    <div class="card why-card">
        <h3>Unified operations</h3>
        <p>Flights, hotels, holiday packages, buses, trains, and cabs are managed from one admin panel with role-based access.</p>
    </div>
    <div class="card why-card">
        <h3>Data-driven storefront</h3>
        <p>Featured sections and destination links update from your catalogue — no static placeholder content on this page.</p>
    </div>
    <div class="card why-card">
        <h3>Built to scale</h3>
        <p>Structured for payment gateways, GST-compliant invoicing, and future B2B or corporate travel modules.</p>
    </div>
</div>

@include('partials.home-featured-sections')
@endsection
