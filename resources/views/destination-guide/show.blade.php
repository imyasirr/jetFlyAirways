@extends('layouts.app')

@section('body_class', 'page-cms page-destination-guide')

@section('title', $page->title.' — Jet Fly Airways')
@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($settings->intro ?? ''), 160))

@section('full')
    <div class="jfa-cms-hero jfa-cms-hero--guide">
        <div class="jfa-container">
            <nav class="jfa-breadcrumb jfa-breadcrumb--light" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                <span aria-current="page">{{ $page->title }}</span>
            </nav>
            <div class="jfa-cms-hero__head">
                <span class="material-symbols-outlined filled jfa-cms-hero__icon">travel_explore</span>
                <h1>{{ $page->title }}</h1>
            </div>
            @if(filled($page->meta_description))
                <p class="jfa-cms-hero__desc">{{ $page->meta_description }}</p>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="jfa-cms-card cms-page jfa-dest-guide">
        @if(filled($settings->intro))
            <p class="cms-lead">{{ $settings->intro }}</p>
        @endif

        @if($features->isNotEmpty())
            <div class="jfa-guide-features">
                @foreach($features as $feature)
                    <article class="jfa-guide-feature">
                        @if(filled($feature->icon))
                            <span class="jfa-guide-feature__icon material-symbols-outlined">{{ $feature->icon }}</span>
                        @endif
                        <h3>{{ $feature->title }}</h3>
                        <p>{{ $feature->body }}</p>
                    </article>
                @endforeach
            </div>
        @endif

        @if($spots->isNotEmpty())
            <div class="jfa-guide-section-head">
                <h2>{{ $settings->spots_heading ?: 'Trending destinations' }}</h2>
                @if(filled($settings->spots_subheading))
                    <p>{{ $settings->spots_subheading }}</p>
                @endif
            </div>

            <div class="cms-dest-grid">
                @foreach($spots as $spot)
                    <a class="cms-dest-card" href="{{ $spot->href() }}">
                        @if($spot->imageUrl())
                            <img src="{{ $spot->imageUrl() }}" alt="{{ $spot->name }}" loading="lazy">
                        @else
                            <div class="cms-dest-card__fallback" aria-hidden="true">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                        @endif
                        <span class="cms-dest-info">
                            <strong>{{ $spot->name }}</strong>
                            @if(filled($spot->subtitle()))
                                <span>{{ $spot->subtitle() }}</span>
                            @endif
                        </span>
                    </a>
                @endforeach
            </div>
        @endif

        @if($tips->isNotEmpty())
            <div class="jfa-guide-section-head">
                <h2>{{ $settings->tips_heading ?: 'Quick planning tips' }}</h2>
            </div>
            <ul class="jfa-guide-tips">
                @foreach($tips as $tip)
                    <li>
                        <strong>{{ $tip->title }}</strong>
                        @if(filled($tip->body))
                            {{ $tip->body }}
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        @if(filled($settings->callout_title) || filled($settings->callout_body))
            <p class="cms-callout">
                @if(filled($settings->callout_title))
                    <strong>{{ $settings->callout_title }}</strong>
                @endif
                {{ $settings->callout_body }}
                @if(filled($settings->callout_link))
                    <a href="{{ str_starts_with($settings->callout_link, 'http') ? $settings->callout_link : url($settings->callout_link) }}">
                        {{ $settings->callout_link_label ?: 'Learn more' }}
                    </a>
                @endif
            </p>
        @endif

        <div class="cms-actions">
            <a class="btn" href="{{ route('module.index', 'flights') }}">Search flights</a>
            <a class="btn secondary" href="{{ route('module.index', 'packages') }}">Browse packages</a>
        </div>
    </div>
@endsection
