@extends('layouts.app')

@section('body_class', 'page-cms')

@section('title', $page->title.' — Jet Fly Airways')

@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->body), 160))

@section('full')
    @php
        $pageBanner = \App\Models\PageBanner::forKey(\App\Models\PageBanner::cmsPageKey($page->slug));
        $cmsHeroImage = $pageBanner?->imageUrl()
            ?? (($page->hero_image ?? null)
                ? \App\Support\PublicImageStorage::url($page->hero_image)
                : null);
        $heroDescription = $pageBanner?->subtitle ?: $page->meta_description;
    @endphp

    @include('partials.jfa-page-hero', [
        'title' => $page->title,
        'description' => $heroDescription,
        'accentColor' => '#003B95',
        'bannerImage' => $cmsHeroImage,
        'heroClass' => 'jfa-cms-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $page->title],
        ],
    ])
@endsection

@section('content')
    <div class="jfa-cms-card cms-page {{ $page->slug === 'sitemap' ? 'cms-page--wide' : '' }}">
        @if(in_array($page->slug, ['about', 'careers'], true))
            <nav class="jfa-cms-discover-nav" aria-label="Discover section">
                <a href="{{ route('welcome') }}">Discover</a>
                <a href="{{ route('pages.show', 'about') }}" @class(['is-active' => $page->slug === 'about'])>About us</a>
                <a href="{{ route('jobs.index') }}">Open roles</a>
                <a href="{{ route('pages.show', 'careers') }}" @class(['is-active' => $page->slug === 'careers'])>Careers</a>
            </nav>
        @endif
        <article class="cms-page-prose">
            {!! $page->body !!}
        </article>
    </div>
@endsection
