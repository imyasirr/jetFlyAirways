@if(isset($offers) && $offers->isNotEmpty())
    <h2 class="home-section-heading" style="margin-top:4px;">Offers &amp; deals</h2>
    <div class="home-deals-grid">
        @foreach($offers as $offer)
            <article class="home-deal-card">
                <div class="home-deal-card__media" aria-hidden="true">🏷️</div>
                <div class="home-deal-card__body">
                    <h3 class="card-title">{{ $offer->title }}</h3>
                    @if($offer->description)
                        <p class="card-meta">{{ \Illuminate\Support\Str::limit(strip_tags($offer->description), 120) }}</p>
                    @endif
                    @if($offer->redirect_url)
                        <a class="btn secondary btn-block" href="{{ $offer->redirect_url }}">Grab deal</a>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endif
