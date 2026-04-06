@include('partials.home-offers')

<div class="home-dest-block">
    <h2 class="home-section-heading">Popular destinations</h2>
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
            <div class="home-deal-card__media" aria-hidden="true">✈</div>
            <div class="home-deal-card__body">
                <h3 class="card-title">{{ $f->airline }} {{ $f->flight_number }}</h3>
                <p class="card-meta">{{ $f->from_city }} → {{ $f->to_city }}</p>
                <p class="card-price">From Rs {{ number_format($f->price, 2) }}</p>
                <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'flights', 'id' => $f->id]) }}">View details</a>
            </div>
        </article>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No flights in database yet — add from Admin → Flights.</p>
    @endforelse
</div>

<h2 class="home-section-heading">Featured hotels</h2>
<div class="home-deals-grid">
    @forelse($featuredHotels as $h)
        <article class="home-deal-card">
            <div class="home-deal-card__media" aria-hidden="true">🏨</div>
            <div class="home-deal-card__body">
                <h3 class="card-title">{{ $h->name }}</h3>
                <p class="card-meta">{{ $h->city }} · {{ $h->star_rating }}★</p>
                <p class="card-price">From Rs {{ number_format($h->price_per_night, 2) }}/night</p>
                <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'hotels', 'id' => $h->id]) }}">View details</a>
            </div>
        </article>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No hotels yet — add from Admin → Hotels.</p>
    @endforelse
</div>

<h2 class="home-section-heading">Holiday picks</h2>
<div class="home-deals-grid">
    @forelse($featuredPackages as $p)
        <article class="home-deal-card">
            <div class="home-deal-card__media" aria-hidden="true">🌴</div>
            <div class="home-deal-card__body">
                <h3 class="card-title">{{ $p->name }}</h3>
                <p class="card-meta">{{ $p->destination }} · {{ $p->duration_days }} days</p>
                <p class="card-price">From Rs {{ number_format($p->offer_price ?? $p->price, 2) }}</p>
                <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'packages', 'id' => $p->id]) }}">View details</a>
            </div>
        </article>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No packages yet — add from Admin → Travel Packages.</p>
    @endforelse
</div>

@if(isset($testimonials) && $testimonials->isNotEmpty())
    <h2 class="home-section-heading">What travellers say</h2>
    <div class="home-deals-grid">
        @foreach($testimonials as $t)
            <article class="home-deal-card">
                <div class="home-deal-card__media" aria-hidden="true">⭐</div>
                <div class="home-deal-card__body">
                    <p class="card-meta" style="margin-bottom:8px;">{{ str_repeat('★', (int) $t->rating) }}</p>
                    <p class="card-meta" style="font-style:italic;">“{{ $t->review }}”</p>
                    <p class="card-title" style="margin-top:12px;margin-bottom:0;">{{ $t->name }}</p>
                    @if($t->designation)<p class="card-meta" style="margin:4px 0 0;">{{ $t->designation }}</p>@endif
                </div>
            </article>
        @endforeach
    </div>
@endif

<div class="feature-row feature-row-spaced home-trust-cards">
    <div class="card"><h3>Secure payments</h3><p>Checkout flow ready for gateway integration (Razorpay, UPI, cards).</p></div>
    <div class="card"><h3>Best-value fares</h3><p>Admin-managed inventory with live listing on the website.</p></div>
    <div class="card"><h3>Full admin control</h3><p>Flights, hotels, packages, routes, cabs, bookings — from one dashboard.</p></div>
</div>
