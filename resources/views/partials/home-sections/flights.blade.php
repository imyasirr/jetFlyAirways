<section class="jfa-section jfa-section--white">
    <div class="jfa-container">
        <div class="jfa-section-head">
            <div>
                <h2 class="jfa-section-title">Featured flights</h2>
                <p class="jfa-section-sub">Hand-picked deals on popular routes</p>
            </div>
            <a href="{{ route('module.index', 'flights') }}" class="jfa-link-arrow">All Flights <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span></a>
        </div>
        <div class="jfa-grid jfa-grid--3">
            @forelse($featuredFlights as $f)
                <article class="jfa-listing-card">
                    <div class="jfa-listing-card__body">
                        <h3 class="jfa-listing-card__title">{{ $f->airline }} {{ $f->flight_number }}</h3>
                        <p class="jfa-listing-card__sub">{{ $f->from_city }} → {{ $f->to_city }}</p>
                        <div class="jfa-listing-card__foot">
                            <div>
                                <div class="jfa-listing-card__price-label">From</div>
                                <div class="jfa-listing-card__price">₹{{ number_format($f->price, 0) }}</div>
                            </div>
                            <a class="btn" href="{{ route('module.show', ['module' => 'flights', 'item' => $f->slug]) }}" style="padding:10px 18px;font-size:14px;">View</a>
                        </div>
                    </div>
                </article>
            @empty
                <p style="grid-column:1/-1;color:var(--jfa-muted);">No flights yet — add from admin.</p>
            @endforelse
        </div>
    </div>
</section>
