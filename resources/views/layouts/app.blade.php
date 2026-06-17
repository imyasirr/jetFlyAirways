<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $seoBrand = ($siteSetting ?? null)?->brand_name ?: 'Jet Fly Airways';
        $seoDefaultTitle = ($siteSeo ?? null)?->meta_title ?: ($seoBrand . ' - Book Flights, Hotels & Holidays');
        $seoDefaultDesc = ($siteSeo ?? null)?->meta_description ?: ($seoBrand . ' - search flights, hotels, buses, trains, cabs and holiday packages with live inventory.');
        $seoPageTitle = trim($__env->yieldContent('title', $seoDefaultTitle));
        $seoPageDesc = trim($__env->yieldContent('meta_description', $seoDefaultDesc));
        $seoIsHome = request()->routeIs('home') || request()->routeIs('welcome');
        $seoOgImage = ($siteSeo ?? null)?->og_image ? \App\Support\PublicImageStorage::url($siteSeo->og_image) : null;
    @endphp
    <title>{{ $seoPageTitle }}</title>
    <meta name="description" content="{{ $seoPageDesc }}">
    @if(($siteSeo ?? null)?->keywords)<meta name="keywords" content="{{ $siteSeo->keywords }}">@endif
    @if(($siteSeo ?? null)?->robots)<meta name="robots" content="{{ $siteSeo->robots }}">@endif
    @if($seoIsHome && ($siteSeo ?? null)?->canonical_url)
        <link rel="canonical" href="{{ $siteSeo->canonical_url }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif
    <meta property="og:site_name" content="{{ $seoBrand }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoIsHome && ($siteSeo ?? null)?->og_title ? $siteSeo->og_title : $seoPageTitle }}">
    <meta property="og:description" content="{{ $seoIsHome && ($siteSeo ?? null)?->og_description ? $siteSeo->og_description : $seoPageDesc }}">
    @if($seoOgImage)
        <meta property="og:image" content="{{ $seoOgImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $seoOgImage }}">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $seoPageTitle }}">
    <meta name="twitter:description" content="{{ $seoPageDesc }}">
    @if($seoIsHome && ($siteSeo ?? null)?->schema_markup)
        <script type="application/ld+json">{!! $siteSeo->schema_markup !!}</script>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jfa-website.css') }}?v=5">
    @stack('styles')
</head>
<body class="jfa-page @yield('body_class')">
    @include('partials.ui-extras')
    @include('partials.welcome-popup')
    @include('partials.jfa-header')
    <main>
        @yield('full')
        <div class="jfa-container">
            @yield('content')
        </div>
    </main>
    @include('partials.jfa-footer')
    @include('partials.jfa-floating')
    @include('partials.flash-swal', ['swalConfirmColor' => '#003B95'])
    @stack('scripts')
</body>
</html>
