@extends('layouts.app')

@section('body_class', 'page-cms page-destination-detail')

@section('title', $destination->name.' — Jet Fly Airways')

@section('meta_description', Str::limit(strip_tags($destination->description ?? $destination->details ?? 'Explore '.$destination->name.' with Jet Fly Airways holiday packages.'), 160))

@section('full')
    @include('partials.jfa-page-hero', [
        'title' => $destination->name,
        'description' => $destination->tag_line,
        'accentColor' => '#003B95',
        'bannerImage' => $destination->bannerUrl(),
        'heroClass' => 'jfa-cms-hero jfa-dest-detail-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Destinations', 'url' => route('home').'#popular-destinations'],
            ['label' => $destination->name],
        ],
    ])
@endsection

@section('content')
    <div class="jfa-cms-card cms-page jfa-dest-detail">
        @if($destination->best_season)
            <div class="jfa-dest-detail__facts" aria-label="Quick facts">
                <div class="jfa-dest-detail__fact">
                    <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                    <div>
                        <strong>Best season</strong>
                        <span>{{ $destination->best_season }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if(filled($destination->description))
            <div class="jfa-dest-detail__section">
                <h2 class="jfa-dest-detail__heading">Overview</h2>
                <article class="cms-page-prose jfa-dest-detail__prose">
                    {!! $destination->description !!}
                </article>
            </div>
        @endif

        @if($destination->gallery->isNotEmpty())
            <div class="jfa-dest-detail__section">
                <h2 class="jfa-dest-detail__heading">Photo gallery</h2>
                <div class="jfa-dest-gallery">
                    @foreach($destination->gallery as $image)
                        <figure class="jfa-dest-gallery__item">
                            <img src="{{ $image->imageUrl() }}" alt="{{ $image->caption ?: $destination->name }}" loading="lazy">
                            @if($image->caption)
                                <figcaption>{{ $image->caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </div>
        @endif

        @if(filled($destination->details))
            <div class="jfa-dest-detail__section">
                <h2 class="jfa-dest-detail__heading">Travel details</h2>
                <article class="cms-page-prose jfa-dest-detail__prose">
                    {!! $destination->details !!}
                </article>
            </div>
        @endif

        @if(! filled($destination->description) && ! filled($destination->details))
            <p class="cms-lead">Explore holiday packages and curated itineraries for {{ $destination->name }}.</p>
        @endif

        <div class="jfa-dest-detail__actions">
            <a href="{{ $destination->packagesUrl() }}" class="jfa-hero__cta">
                View holiday packages
                <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
            <a href="{{ route('home') }}" class="btn secondary">Back to home</a>
        </div>
    </div>
@endsection
