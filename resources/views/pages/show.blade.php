@extends('layouts.app')

@section('body_class', 'page-cms')

@section('title', $page->title.' — Jet Fly Airways')

@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->body), 160))

@section('full')
    @php
        $cmsHeroImage = ($page->hero_image ?? null)
            ? \App\Support\PublicImageStorage::url($page->hero_image)
            : null;
    @endphp

    @include('partials.jfa-page-hero', [
        'title' => $page->title,
        'description' => $page->meta_description,
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
        <article class="cms-page-prose">
            {!! $page->body !!}
        </article>
    </div>
@endsection
