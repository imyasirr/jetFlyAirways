@extends('layouts.app')

@section('body_class', 'page-destination-detail')

@section('title', $destination->name.' — Jet Fly Airways')

@section('meta_description', Str::limit(strip_tags($destination->description ?? $destination->details ?? 'Explore '.$destination->name.' with Jet Fly Airways holiday packages.'), 160))

@section('full')
    @include('partials.jfa-page-hero', [
        'title' => $destination->name,
        'tagLine' => $destination->tag_line,
        'description' => null,
        'accentColor' => '#003B95',
        'bannerImage' => $destination->bannerUrl(),
        'heroClass' => 'jfa-dest-detail-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Destinations', 'url' => route('home').'#popular-destinations'],
            ['label' => $destination->name],
        ],
    ])
@endsection

@section('content')
    <div class="jfa-dest-detail">
        <div class="jfa-dest-detail__layout">
            <div class="jfa-dest-detail__main">
                @if(filled($destination->description))
                    <section class="jfa-dest-detail__block" aria-labelledby="dest-overview-heading">
                        <header class="jfa-dest-detail__block-head">
                            <span class="material-symbols-outlined" aria-hidden="true">explore</span>
                            <h2 id="dest-overview-heading">Overview</h2>
                        </header>
                        <article class="cms-page-prose jfa-dest-detail__prose">
                            {!! $destination->description !!}
                        </article>
                    </section>
                @endif

                @if($destination->gallery->isNotEmpty())
                    <section class="jfa-dest-detail__block" aria-labelledby="dest-gallery-heading">
                        <header class="jfa-dest-detail__block-head">
                            <span class="material-symbols-outlined" aria-hidden="true">photo_library</span>
                            <h2 id="dest-gallery-heading">Photo gallery</h2>
                        </header>
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
                    </section>
                @endif

                @if(filled($destination->details))
                    <section class="jfa-dest-detail__block" aria-labelledby="dest-details-heading">
                        <header class="jfa-dest-detail__block-head">
                            <span class="material-symbols-outlined" aria-hidden="true">map</span>
                            <h2 id="dest-details-heading">Travel details</h2>
                        </header>
                        <article class="cms-page-prose jfa-dest-detail__prose">
                            {!! $destination->details !!}
                        </article>
                    </section>
                @endif

                @if(! filled($destination->description) && ! filled($destination->details))
                    <section class="jfa-dest-detail__empty">
                        <span class="material-symbols-outlined" aria-hidden="true">travel_explore</span>
                        <h2>Discover {{ $destination->name }}</h2>
                        <p>Explore curated holiday packages, hand-picked stays, and seamless travel with Jet Fly.</p>
                    </section>
                @endif
            </div>

            <aside class="jfa-dest-detail__aside" aria-label="Trip planning">
                @if($destination->best_season)
                    <div class="jfa-dest-detail__aside-card">
                        <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                        <div>
                            <small>Best season</small>
                            <strong>{{ $destination->best_season }}</strong>
                        </div>
                    </div>
                @endif

                <div class="jfa-dest-detail__book-card">
                    <h3>Plan your trip</h3>
                    <p>Browse holiday packages for {{ $destination->name }} with flights, hotels and sightseeing included.</p>
                    <a href="{{ $destination->packagesUrl() }}" class="jfa-dest-detail__book-btn">
                        View holiday packages
                        <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>

                <a href="{{ route('home') }}#popular-destinations" class="jfa-dest-detail__back">
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                    More destinations
                </a>
            </aside>
        </div>
    </div>
@endsection
