<section class="jfa-section jfa-section--muted">
    <div class="jfa-container">
        <div class="jfa-section-head">
            <div>
                <h2 class="jfa-section-title">Popular Destinations</h2>
                <p class="jfa-section-sub">Trending this season</p>
            </div>
        </div>
        <div class="jfa-grid jfa-grid--6">
            @forelse($topDestinations as $dest)
                <a href="{{ route('module.index', 'packages') }}?destination={{ urlencode($dest) }}" class="jfa-dest">
                    <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&q=80&w=400" alt="{{ $dest }}" loading="lazy">
                    <span class="jfa-dest__overlay"></span>
                    <span class="jfa-dest__text">
                        <span class="jfa-dest__name">{{ $dest }}</span>
                        <span class="jfa-dest__price">Explore packages</span>
                    </span>
                </a>
            @empty
                <p style="grid-column:1/-1;color:var(--jfa-muted);">Add packages in admin to show destinations.</p>
            @endforelse
        </div>
    </div>
</section>
