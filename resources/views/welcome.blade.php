@extends('layouts.app')

@section('title', $siteSeo?->meta_title ?? 'Discover — Jet Fly Airways')

@section('meta_description', $siteSeo?->meta_description ?? 'Jet Fly Airways enterprise travel platform — flights, hotels, packages, buses, trains and cabs with live inventory.')

@push('styles')
<style>
    .hero-welcome { padding:48px 0 56px; }
    .hero-welcome h1 { max-width:18ch; }
    .hero-welcome .lead { font-size:1.1rem; max-width:56ch; opacity:.95; margin:0 0 28px; line-height:1.55; }
    .stats-strip {
        display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px;
        margin-top:8px; max-width:900px;
    }
    .stat-item {
        background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); border-radius:14px;
        padding:14px 16px; text-align:center;
    }
    .stat-item strong { display:block; font-size:1.5rem; font-weight:800; letter-spacing:-.02em; }
    .stat-item span { font-size:11px; text-transform:uppercase; letter-spacing:.06em; opacity:.85; margin-top:4px; display:block; }
    .why-grid { display:grid; gap:20px; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); margin-bottom:8px; }
    .why-card { border-left:4px solid var(--accent); padding-left:18px; }
    .why-card h3 { margin:0 0 8px; font-size:1.05rem; color:var(--primary); }
    .why-card p { margin:0; font-size:14px; color:var(--muted); line-height:1.55; }
</style>
@endpush

@section('full')
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

<div class="home-banner-page">
    <div class="container">
        @include('partials.home-banner-slider')
    </div>
</div>

@include('partials.home-search-panel')
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
