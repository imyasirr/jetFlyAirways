<section class="jfa-section jfa-section--muted">
    <div class="jfa-container">
        <div class="jfa-section-head">
            <div>
                <h2 class="jfa-section-title">Featured hotels</h2>
                <p class="jfa-section-sub">Luxury to budget — best picks</p>
            </div>
            <a href="{{ route('module.index', 'hotels') }}" class="jfa-link-arrow">All Hotels <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span></a>
        </div>
        <div class="jfa-grid jfa-grid--3">
            @forelse($featuredHotels as $h)
                <article class="jfa-listing-card">
                    <div class="jfa-listing-card__body">
                        <h3 class="jfa-listing-card__title">{{ $h->name }}</h3>
                        <p class="jfa-listing-card__sub">{{ $h->city }} · {{ $h->star_rating }}★</p>
                        <div class="jfa-listing-card__foot">
                            <div>
                                <div class="jfa-listing-card__price-label">From</div>
                                <div class="jfa-listing-card__price">₹{{ number_format($h->price_per_night, 0) }}<span style="font-size:12px;font-weight:400;color:var(--jfa-muted);">/night</span></div>
                            </div>
                            <a class="btn" href="{{ route('module.show', ['module' => 'hotels', 'item' => $h->slug]) }}" style="padding:10px 18px;font-size:14px;">View</a>
                        </div>
                    </div>
                </article>
            @empty
                <p style="grid-column:1/-1;color:var(--jfa-muted);">No hotels yet.</p>
            @endforelse
        </div>
    </div>
</section>
