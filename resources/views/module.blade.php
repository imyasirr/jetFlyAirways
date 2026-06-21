@extends('layouts.app')

@section('body_class', 'page-module')

@php
    $moduleMeta = [
        'flights' => ['icon' => 'flight', 'color' => '#003B95', 'desc' => 'Search and book cheap flights across 500+ routes in India and worldwide.'],
        'hotels' => ['icon' => 'hotel', 'color' => '#0d9488', 'desc' => 'Book 50,000+ hotels across India. Best rates, instant confirmation.'],
        'packages' => ['icon' => 'beach_access', 'color' => '#f97316', 'desc' => 'Curated holiday packages with flights, hotels, meals and sightseeing included.'],
        'buses' => ['icon' => 'directions_bus', 'color' => '#b45309', 'desc' => 'Book AC/Non-AC buses across 10,000+ routes. Fast, safe and affordable.'],
        'trains' => ['icon' => 'train', 'color' => '#7c3aed', 'desc' => 'Book Indian Railways tickets with live PNR status. All classes, all routes.'],
        'cabs' => ['icon' => 'local_taxi', 'color' => '#0369a1', 'desc' => 'Book outstation cabs, airport transfers and one-way drops across India.'],
        'visa' => ['icon' => 'travel_explore', 'color' => '#be185d', 'desc' => 'Hassle-free visa assistance for 100+ countries. Document help, application support.'],
        'insurance' => ['icon' => 'shield', 'color' => '#047857', 'desc' => 'Comprehensive travel insurance for domestic and international trips.'],
    ];
    $meta = $moduleMeta[$slug] ?? $moduleMeta['flights'];
    $hasSidebar = empty($staticModule) && empty($addonCatalog) && ! in_array($slug, ['visa', 'insurance'], true);
    $resultCount = isset($items) && is_object($items) && method_exists($items, 'total') ? $items->total() : (is_countable($items ?? null) ? count($items) : 0);
@endphp

@section('full')
    @php
        $heroDescription = $pageBanner?->subtitle;
        if (! filled($heroDescription)) {
            if (! empty($addonCatalog)) {
                $heroDescription = __('jetfly.module_addon_intro');
            } elseif (! empty($staticModule)) {
                $heroDescription = __('jetfly.module_static_addon');
            } else {
                $heroDescription = $meta['desc'];
            }
        }
    @endphp

    @include('partials.jfa-page-hero', [
        'title' => $module['title'],
        'description' => $heroDescription,
        'icon' => $meta['icon'],
        'accentColor' => $meta['color'],
        'bannerImage' => $pageBanner?->imageUrl(),
        'heroClass' => 'jfa-module-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $module['title']],
        ],
    ])

    @if(empty($staticModule) && empty($addonCatalog))
        <div class="jfa-container jfa-module-search-wrap">
            @include('partials.home-search-panel', ['activeModule' => $slug, 'compact' => true])
        </div>
    @endif

    <div class="jfa-container jfa-module-body">
        @if(!empty($staticModule))
            <div class="jfa-card jfa-module-empty">
                <p>{{ __('jetfly.module_static_addon') }}</p>
                <div class="form-actions" style="justify-content:center;">
                    <a class="btn secondary" href="{{ route('contact.create') }}">{{ __('jetfly.contact_us') }}</a>
                    <a class="btn" href="{{ route('home') }}">{{ __('jetfly.back_home') }}</a>
                </div>
            </div>
        @else
            @if($slug === 'trains' && empty($addonCatalog))
                <div class="jfa-pnr-widget">
                    <div class="jfa-pnr-widget__head">
                        <span class="material-symbols-outlined filled">train</span>
                        <h3>PNR status check</h3>
                    </div>
                    <form method="get" action="{{ route('module.index', 'trains') }}" class="jfa-pnr-widget__form">
                        @foreach(request()->except('pnr') as $key => $val)
                            @if(!is_array($val))
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endif
                        @endforeach
                        <input name="pnr" value="{{ request('pnr') }}" placeholder="Enter 10-digit PNR" maxlength="12" aria-label="PNR number">
                        <button type="submit" class="btn">Check</button>
                    </form>
                    @if(!empty($trainPnrResult))
                        <div class="jfa-pnr-widget__result {{ !empty($trainPnrResult['ok']) ? 'is-success' : 'is-error' }}">
                            @if(!empty($trainPnrResult['ok']))
                                <p><strong>PNR {{ $trainPnrResult['pnr'] }}</strong> — {{ $trainPnrResult['message'] }}</p>
                                <p class="jfa-pnr-widget__meta">{{ $trainPnrResult['train'] ?? '' }} · {{ $trainPnrResult['from'] ?? '' }} → {{ $trainPnrResult['to'] ?? '' }}</p>
                            @else
                                <p>{{ $trainPnrResult['message'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="jfa-module-layout">
                @if($hasSidebar)
                    <button type="button" class="jfa-filter-mobile-toggle" id="jfa-filter-toggle" aria-expanded="false" aria-controls="jfa-filter-sidebar">
                        <span class="material-symbols-outlined">tune</span>
                        Filters
                    </button>

                    <aside class="jfa-filter-sidebar" id="jfa-filter-sidebar">
                        <div class="jfa-filter-sidebar__card">
                            <div class="jfa-filter-sidebar__head">
                                <h3>Filters</h3>
                                <a href="{{ route('module.index', $slug) }}" class="jfa-filter-sidebar__reset">Reset</a>
                            </div>
                            @include('partials.module-filter-sidebar')
                        </div>
                    </aside>
                @endif

                <div class="jfa-module-results">
                    <div class="jfa-module-sortbar">
                        <span class="jfa-module-sortbar__count">{{ number_format($resultCount) }} results found</span>
                        @if($hasSidebar && $resultCount > 0)
                            <label class="jfa-module-sortbar__sort">
                                <span>Sort by:</span>
                                <select name="sort" form="jfa-module-filters" onchange="document.getElementById('jfa-module-filters')?.requestSubmit()">
                                    <option value="">Recommended</option>
                                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                                </select>
                            </label>
                        @endif
                    </div>

                    <div class="jfa-grid jfa-grid--3">
                        @forelse($items ?? [] as $item)
                            <article class="jfa-listing-card">
                                <div class="jfa-listing-card__body">
                                    <h3 class="jfa-listing-card__title">{{ $item['title'] }}</h3>
                                    <p class="jfa-listing-card__sub">{{ $item['subtitle'] }}</p>
                                    <div class="jfa-listing-card__foot">
                                        @if(($item['price'] ?? 0) > 0)
                                            <div>
                                                <div class="jfa-listing-card__price-label">From</div>
                                                <div class="jfa-listing-card__price">₹{{ number_format($item['price'], 0) }}</div>
                                            </div>
                                        @endif
                                        @if(!empty($item['external']))
                                            <a class="btn" href="{{ $item['book_url'] ?? route('contact.create') }}" style="padding:10px 18px;font-size:14px;">Request</a>
                                        @else
                                            <a class="btn" href="{{ route('module.show', ['module' => $slug, 'item' => $item['slug']]) }}" style="padding:10px 18px;font-size:14px;">View Details</a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="jfa-card jfa-module-empty" style="grid-column:1/-1;">
                                <span class="material-symbols-outlined">search_off</span>
                                <h3>No results found</h3>
                                <p>Try adjusting your filters or search for a different route.</p>
                                <a class="btn" href="{{ route('contact.create') }}">Contact Support</a>
                            </div>
                        @endforelse
                    </div>

                    @if(isset($items) && is_object($items) && method_exists($items, 'hasPages') && $items->hasPages())
                        <div class="jfa-module-pagination">{{ $items->links() }}</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var toggle = document.getElementById('jfa-filter-toggle');
    var sidebar = document.getElementById('jfa-filter-sidebar');
    if (!toggle || !sidebar) return;
    toggle.addEventListener('click', function () {
        var open = sidebar.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
})();
</script>
@endpush
