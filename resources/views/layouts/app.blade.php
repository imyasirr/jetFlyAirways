<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Jet Fly Airways - Book Flights, Hotels & Holidays')</title>
    <meta name="description" content="@yield('meta_description', 'Jet Fly Airways - search flights, hotels, buses, trains, cabs and holiday packages with live inventory.')">
    @if(isset($siteSeo) && $siteSeo && (request()->routeIs('home') || request()->routeIs('welcome')))
        @if($siteSeo->keywords)<meta name="keywords" content="{{ $siteSeo->keywords }}">@endif
        @if($siteSeo->canonical_url)<link rel="canonical" href="{{ $siteSeo->canonical_url }}">@endif
        @if($siteSeo->robots)<meta name="robots" content="{{ $siteSeo->robots }}">@endif
        @if($siteSeo->og_title)<meta property="og:title" content="{{ $siteSeo->og_title }}">@endif
        @if($siteSeo->og_description)<meta property="og:description" content="{{ $siteSeo->og_description }}">@endif
        @php $ogImageResolved = \App\Support\PublicImageStorage::url($siteSeo->og_image); @endphp
        @if($ogImageResolved)<meta property="og:image" content="{{ $ogImageResolved }}">@endif
        @if($siteSeo->schema_markup)
            <script type="application/ld+json">{!! $siteSeo->schema_markup !!}</script>
        @endif
    @endif
    <link rel="stylesheet" href="{{ asset('css/public.css') }}?v=7">
    @stack('styles')
</head>
<body class="@yield('body_class')">
    @include('partials.welcome-popup')
    @include('partials.public-header')
    <main>
        @yield('full')
        <div class="container">
            @yield('content')
        </div>
    </main>
    @include('partials.public-footer')
    @include('partials.whatsapp-float')
    @include('partials.flash-swal', ['swalConfirmColor' => '#008cff'])
    <script>
        (function () {
            var key = 'jetfly-theme';
            var root = document.documentElement;
            var saved = localStorage.getItem(key);
            if (saved === 'dark' || saved === 'light') {
                root.setAttribute('data-theme', saved);
            }
            document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    root.setAttribute('data-theme', next);
                    localStorage.setItem(key, next);
                });
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>

