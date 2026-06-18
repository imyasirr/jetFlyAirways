@php
    use App\Support\PublicImageStorage;

    $seoBrand = ($siteSetting ?? null)?->brand_name ?: 'Jet Fly Airways';
    $seoDefaultTitle = ($siteSeo ?? null)?->meta_title ?: ($seoBrand.' - Book Flights, Hotels & Holidays');
    $seoDefaultDesc = ($siteSeo ?? null)?->meta_description ?: ($seoBrand.' - search flights, hotels, buses, trains, cabs and holiday packages with live inventory.');

    $seoTitleSuffix = $seoTitleSuffix ?? false;
    $seoTitleDefault = $seoTitleDefault ?? $seoDefaultTitle;
    $shortTitle = trim($__env->yieldContent('title', $seoTitleDefault));
    $seoPageTitle = $seoTitleSuffix ? $shortTitle.' — '.$seoBrand : $shortTitle;
    $seoPageDesc = trim($__env->yieldContent('meta_description', $seoDefaultDesc));

    $seoIsHome = request()->routeIs('home') || request()->routeIs('welcome');

    $seoOgTitleOverride = trim($__env->yieldContent('og_title', ''));
    $seoOgDescOverride = trim($__env->yieldContent('og_description', ''));
    $seoOgImageOverride = trim($__env->yieldContent('og_image', ''));
    $seoRobotsOverride = trim($__env->yieldContent('meta_robots', $defaultRobots ?? ''));
    $seoCanonicalOverride = trim($__env->yieldContent('canonical_url', ''));
    $seoOgType = trim($__env->yieldContent('og_type', 'website'));

    $seoOgTitle = $seoOgTitleOverride !== ''
        ? $seoOgTitleOverride
        : (($seoIsHome && filled($siteSeo?->og_title)) ? $siteSeo->og_title : $seoPageTitle);

    $seoOgDescription = $seoOgDescOverride !== ''
        ? $seoOgDescOverride
        : (($seoIsHome && filled($siteSeo?->og_description)) ? $siteSeo->og_description : $seoPageDesc);

    $seoOgImage = null;
    if ($seoOgImageOverride !== '') {
        $seoOgImage = preg_match('#^https?://#i', $seoOgImageOverride)
            ? $seoOgImageOverride
            : PublicImageStorage::url($seoOgImageOverride);
    } elseif (filled($siteSeo?->og_image)) {
        $seoOgImage = PublicImageStorage::url($siteSeo->og_image);
    } elseif (filled($siteSetting?->logo_image)) {
        $seoOgImage = PublicImageStorage::url($siteSetting->logo_image);
    }

    $seoCanonical = $seoCanonicalOverride !== ''
        ? $seoCanonicalOverride
        : (($seoIsHome && filled($siteSeo?->canonical_url)) ? $siteSeo->canonical_url : url()->current());

    $seoRobots = $seoRobotsOverride !== '' ? $seoRobotsOverride : ($siteSeo?->robots ?? null);
@endphp
<title>{{ $seoPageTitle }}</title>
<meta name="description" content="{{ $seoPageDesc }}">
@if(filled($siteSeo?->keywords))
    <meta name="keywords" content="{{ $siteSeo->keywords }}">
@endif
@if(filled($seoRobots))
    <meta name="robots" content="{{ $seoRobots }}">
@endif
<link rel="canonical" href="{{ $seoCanonical }}">
<meta property="og:site_name" content="{{ $seoBrand }}">
<meta property="og:type" content="{{ $seoOgType }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $seoOgTitle }}">
<meta property="og:description" content="{{ $seoOgDescription }}">
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
@if($seoOgImage)
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta property="og:image:secure_url" content="{{ $seoOgImage }}">
    <meta property="og:image:alt" content="{{ $seoOgTitle }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $seoOgImage }}">
    <meta name="twitter:image:alt" content="{{ $seoOgTitle }}">
@else
    <meta name="twitter:card" content="summary">
@endif
<meta name="twitter:title" content="{{ $seoOgTitle }}">
<meta name="twitter:description" content="{{ $seoOgDescription }}">
@if($seoIsHome && filled($siteSeo?->schema_markup))
    <script type="application/ld+json">{!! $siteSeo->schema_markup !!}</script>
@endif
@stack('schema')
