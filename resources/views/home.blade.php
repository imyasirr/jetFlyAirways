@extends('layouts.app')

@section('body_class', 'page-home')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2">
@endpush

@section('title', $siteSeo?->meta_title ?? 'Home — Jet Fly Airways')

@section('meta_description', $siteSeo?->meta_description ?? 'Jet Fly Airways — search flights, hotels, buses, trains, cabs and holiday packages with live inventory.')

@section('full')
@php
    $heroImgUrl = ($siteSetting ?? null)?->hero_image
        ? \App\Support\PublicImageStorage::url($siteSetting->hero_image)
        : null;
@endphp
<section class="home-ota home-ota--hero {{ $heroImgUrl ? 'home-ota--has-photo' : '' }}" aria-label="Book travel">
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
            <p class="home-ota-kicker">Best prices · Easy booking · 24×7 support</p>
            <h1 class="home-ota-title">Book flights, hotels &amp; holidays</h1>
            <p class="home-ota-sub">Search live fares and stays in seconds — flights, hotels, packages, buses, trains &amp; cabs.</p>
        </header>
        @include('partials.home-search-panel')
    </div>
</section>

<div class="container">
    <div class="home-ota-trust" role="list">
        <span class="home-ota-trust-item" role="listitem"><strong>Best fares</strong> on routes you choose</span>
        <span class="home-ota-trust-item" role="listitem"><strong>Flexible search</strong> for hotels &amp; packages</span>
        <span class="home-ota-trust-item" role="listitem"><strong>Secure checkout</strong> ready for UPI &amp; cards</span>
    </div>
</div>

@include('partials.home-banner-slider')
@endsection

@section('content')
@include('partials.home-featured-sections')
@endsection
