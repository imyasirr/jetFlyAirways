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
