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
