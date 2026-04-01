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
