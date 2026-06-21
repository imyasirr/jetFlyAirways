@include('partials.home-offers')

<section class="home-services-section" aria-labelledby="home-services-title">
    <h2 class="home-section-heading home-section-heading--center" id="home-services-title">Browse by services</h2>
    <p class="home-section-subtitle">Everything you need for your next journey.</p>
    <div class="home-service-grid">
        <a class="home-service-card" href="{{ route('module.index', 'hotels') }}">
            <span class="home-service-icon" aria-hidden="true">HT</span>
            <h3>Hotels</h3>
        </a>
        <a class="home-service-card" href="{{ route('module.index', 'flights') }}">
            <span class="home-service-icon" aria-hidden="true">FL</span>
            <h3>Flights</h3>
        </a>
        <a class="home-service-card" href="{{ route('module.index', 'packages') }}">
            <span class="home-service-icon" aria-hidden="true">TR</span>
            <h3>Tours</h3>
        </a>
        <a class="home-service-card" href="{{ route('module.index', 'cabs') }}">
            <span class="home-service-icon" aria-hidden="true">CB</span>
            <h3>Cabs</h3>
        </a>
    </div>
</section>

<div class="home-dest-block">
    <h2 class="home-section-heading home-section-heading--center">Popular destinations</h2>
    <p class="home-section-subtitle">Handpicked routes and holiday picks for your comfort.</p>
    <div class="home-dest-strip">
        @forelse($topDestinations as $dest)
            <a class="home-dest-link" href="{{ route('module.index', 'packages') }}?destination={{ urlencode($dest) }}">{{ $dest }}</a>
        @empty
            <p class="card empty-hint" style="margin:0;">Add packages in admin to show destinations here.</p>
        @endforelse
    </div>
</div>

<h2 class="home-section-heading">Featured flights</h2>
<div class="home-deals-grid">
    @forelse($featuredFlights as $f)
        <article class="home-deal-card">
            <div class="home-deal-card__media home-deal-card__media--flight" aria-hidden="true">FL</div>
            <div class="home-deal-card__body">
                <h3 class="card-title">{{ $f->airline }} {{ $f->flight_number }}</h3>
                <p class="card-meta">{{ $f->from_city }} to {{ $f->to_city }}</p>
                <p class="card-price">From Rs {{ number_format($f->price, 2) }}</p>
                <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'flights', 'item' => $f->slug]) }}">View details</a>
            </div>
        </article>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No flights in database yet - add from Admin > Flights.</p>
    @endforelse
</div>

<h2 class="home-section-heading">Featured hotels</h2>
<div class="home-deals-grid">
    @forelse($featuredHotels as $h)
        <article class="home-deal-card">
            <div class="home-deal-card__media home-deal-card__media--hotel" aria-hidden="true">HT</div>
            <div class="home-deal-card__body">
                <h3 class="card-title">{{ $h->name }}</h3>
                <p class="card-meta">{{ $h->city }} - {{ $h->star_rating }} star</p>
                <p class="card-price">From Rs {{ number_format($h->price_per_night, 2) }}/night</p>
                <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'hotels', 'item' => $h->slug]) }}">View details</a>
            </div>
        </article>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No hotels yet - add from Admin > Hotels.</p>
    @endforelse
</div>

<h2 class="home-section-heading">Holiday picks</h2>
<div class="home-deals-grid">
    @forelse($featuredPackages as $p)
        <article class="home-deal-card">
            <div class="home-deal-card__media home-deal-card__media--package" aria-hidden="true">HL</div>
            <div class="home-deal-card__body">
                <h3 class="card-title">{{ $p->name }}</h3>
                <p class="card-meta">{{ $p->destination }} - {{ $p->duration_days }} days</p>
                <p class="card-price">From Rs {{ number_format($p->offer_price ?? $p->price, 2) }}</p>
                <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'packages', 'item' => $p->slug]) }}">View details</a>
            </div>
        </article>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No packages yet - add from Admin > Travel Packages.</p>
    @endforelse
</div>

@if(isset($testimonials) && $testimonials->isNotEmpty())
    <h2 class="home-section-heading">What travellers say</h2>
    <div class="home-deals-grid">
        @foreach($testimonials as $t)
            <article class="home-deal-card">
                @if($t->photoUrl())
                    <div class="home-deal-card__media home-deal-card__media--review home-deal-card__media--photo">
                        <img src="{{ $t->photoUrl() }}" alt="{{ $t->name }}" loading="lazy" decoding="async">
                    </div>
                @else
                    <div class="home-deal-card__media home-deal-card__media--review" aria-hidden="true">RV</div>
                @endif
                <div class="home-deal-card__body">
                    <p class="card-meta" style="margin-bottom:8px;">Rating {{ (int) $t->rating }}/5</p>
                    <p class="card-meta" style="font-style:italic;">"{{ $t->review }}"</p>
                    <p class="card-title" style="margin-top:12px;margin-bottom:0;">{{ $t->name }}</p>
                    @if($t->designation)<p class="card-meta" style="margin:4px 0 0;">{{ $t->designation }}</p>@endif
                </div>
            </article>
        @endforeach
    </div>
@endif

<div class="feature-row feature-row-spaced home-trust-cards">
    @forelse($trustCards ?? [] as $card)
        <div class="card">
            <h3>{{ $card->title }}</h3>
            <p>{{ $card->description }}</p>
        </div>
    @empty
        <div class="card"><h3>Secure payments</h3><p>Checkout flow ready for gateway integration, UPI and cards.</p></div>
        <div class="card"><h3>Best-value fares</h3><p>Admin-managed inventory with live listing on the website.</p></div>
        <div class="card"><h3>Full admin control</h3><p>Flights, hotels, packages, routes, cabs and bookings from one dashboard.</p></div>
    @endforelse
</div>

<section class="home-cta" aria-label="Travel deals">
    <h2>Save big on your next adventure</h2>
    <p>Unlock exclusive flight, hotel and holiday package discounts with Jet Fly Airways.</p>
    <a href="{{ route('register') }}" class="home-cta-btn">Register now</a>
</section>
