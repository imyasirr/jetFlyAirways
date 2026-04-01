@include('partials.home-offers')

<div style="margin-top:8px;">
    <h2 class="section-title">Popular destinations</h2>
    <div class="grid grid-tight">
        @forelse($topDestinations as $dest)
            <a class="card card-link" href="{{ route('module.index', 'packages') }}?destination={{ urlencode($dest) }}">{{ $dest }}</a>
        @empty
            <p class="card empty-hint" style="grid-column:1/-1;">Add packages in admin to show destinations here.</p>
        @endforelse
    </div>
</div>

<h2 class="section-title section-title-spaced">Featured flights</h2>
<div class="grid">
    @forelse($featuredFlights as $f)
        <div class="card">
            <h3 class="card-title">{{ $f->airline }} {{ $f->flight_number }}</h3>
            <p class="card-meta">{{ $f->from_city }} → {{ $f->to_city }}</p>
            <p class="card-price">From Rs {{ number_format($f->price, 2) }}</p>
            <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'flights', 'id' => $f->id]) }}">View</a>
        </div>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No flights in database yet — add from Admin → Flights.</p>
    @endforelse
</div>

<h2 class="section-title section-title-spaced">Featured hotels</h2>
<div class="grid">
    @forelse($featuredHotels as $h)
        <div class="card">
            <h3 class="card-title">{{ $h->name }}</h3>
            <p class="card-meta">{{ $h->city }} · {{ $h->star_rating }}★</p>
            <p class="card-price">From Rs {{ number_format($h->price_per_night, 2) }}/night</p>
            <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'hotels', 'id' => $h->id]) }}">View</a>
        </div>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No hotels yet — add from Admin → Hotels.</p>
    @endforelse
</div>

<h2 class="section-title section-title-spaced">Holiday picks</h2>
<div class="grid">
    @forelse($featuredPackages as $p)
        <div class="card">
            <h3 class="card-title">{{ $p->name }}</h3>
            <p class="card-meta">{{ $p->destination }} · {{ $p->duration_days }} days</p>
            <p class="card-price">From Rs {{ number_format($p->offer_price ?? $p->price, 2) }}</p>
            <a class="btn secondary btn-block" href="{{ route('module.show', ['module' => 'packages', 'id' => $p->id]) }}">View</a>
        </div>
    @empty
        <p class="card empty-hint" style="grid-column:1/-1;">No packages yet — add from Admin → Travel Packages.</p>
    @endforelse
</div>

@if(isset($testimonials) && $testimonials->isNotEmpty())
    <h2 class="section-title section-title-spaced-lg">What travellers say</h2>
    <div class="grid">
        @foreach($testimonials as $t)
            <div class="card">
                <p class="card-meta" style="margin-bottom:8px;">{{ str_repeat('★', (int) $t->rating) }}</p>
                <p class="card-meta" style="font-style:italic;">“{{ $t->review }}”</p>
                <p class="card-title" style="margin-top:12px;margin-bottom:0;">{{ $t->name }}</p>
                @if($t->designation)<p class="card-meta" style="margin:4px 0 0;">{{ $t->designation }}</p>@endif
            </div>
        @endforeach
    </div>
@endif

<h2 class="section-title section-title-spaced-lg">All travel services</h2>
<div class="grid">
    @foreach($modules as $slug => $module)
        <div class="card">
            <h3 class="card-title-lg">{{ $module['icon'] }} {{ $module['title'] }}</h3>
            <ul class="card-list">
                @foreach(array_slice($module['features'], 0, 3) as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
            <a class="btn btn-block" href="{{ route('module.index', $slug) }}">Explore</a>
        </div>
    @endforeach
</div>

<div class="feature-row feature-row-spaced">
    <div class="card"><h3>Secure payments</h3><p>Checkout flow ready for gateway integration (Razorpay, UPI, cards).</p></div>
    <div class="card"><h3>Best-value fares</h3><p>Admin-managed inventory with live listing on the website.</p></div>
    <div class="card"><h3>Full admin control</h3><p>Flights, hotels, packages, routes, cabs, bookings — from one dashboard.</p></div>
</div>
