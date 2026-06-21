<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-head')
    @include('partials.head-fonts')
    <link rel="stylesheet" href="{{ asset('css/jfa-website.css') }}?v=21">
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
