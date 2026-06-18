<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-head', ['seoTitleSuffix' => true, 'seoTitleDefault' => 'Sign in'])
    @include('partials.head-fonts')
    <link rel="stylesheet" href="{{ asset('css/jfa-website.css') }}?v=8">
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
