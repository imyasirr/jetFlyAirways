<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sign in') — {{ ($siteSetting ?? null)?->brand_name ?: 'Jet Fly Airways' }}</title>
    @if(($siteSeo ?? null)?->meta_description)<meta name="description" content="{{ $siteSeo->meta_description }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jfa-website.css') }}?v=1">
</head>
<body>
    @include('partials.ui-extras')
    @php
        $bName = $siteSetting?->brand_name ?? 'Jet Fly Airways';
        $bTag = $siteSetting?->brand_tagline ?? 'Book · Fly · Stay';
        $logoUrl = ($siteSetting ?? null)?->logo_image
            ? \App\Support\PublicImageStorage::url($siteSetting->logo_image)
            : null;
    @endphp
    <div class="jfa-auth-page">
        <div class="jfa-auth-page__top">
            <a href="{{ route('home') }}" class="jfa-brand">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $bName }}" class="jfa-brand__logo">
                @else
                    <span class="jfa-brand__icon"><span class="material-symbols-outlined filled">flight</span></span>
                @endif
                <span>
                    <span class="jfa-brand__name">{{ $bName }}</span>
                    <span class="jfa-brand__tag">{{ $bTag }}</span>
                </span>
            </a>
        </div>
        <div class="jfa-auth-page__body">
            <div class="jfa-auth-card">
                @yield('content')
            </div>
        </div>
        <p class="jfa-auth-foot">&copy; {{ date('Y') }} {{ $bName }}</p>
    </div>
    @include('partials.flash-swal', ['swalConfirmColor' => '#003B95'])
</body>
</html>
