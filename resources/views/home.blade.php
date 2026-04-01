@extends('layouts.app')

@section('title', $siteSeo?->meta_title ?? 'Home — Jet Fly Airways')

@section('meta_description', $siteSeo?->meta_description ?? 'Jet Fly Airways — search flights, hotels, buses, trains, cabs and holiday packages with live inventory.')

@section('full')
<section class="hero">
    <div class="container">
        <span class="pill">Trusted travel · Best fares · 24×7 support</span>
        <h1>Explore the world with Jet Fly Airways</h1>
        <p>Book flights, stays, buses, trains, cabs and holiday packages — one place, premium experience.</p>
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
@include('partials.home-featured-sections')
@endsection
