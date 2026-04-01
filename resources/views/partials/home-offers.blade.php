@if(isset($offers) && $offers->isNotEmpty())
    <div style="margin-top:8px;">
        <h2 class="section-title">Offers &amp; deals</h2>
        <div class="grid">
            @foreach($offers as $offer)
                <div class="card">
                    <h3 class="card-title">{{ $offer->title }}</h3>
                    @if($offer->description)
                        <p class="card-meta">{{ \Illuminate\Support\Str::limit(strip_tags($offer->description), 120) }}</p>
                    @endif
                    @if($offer->redirect_url)
                        <a class="btn secondary btn-block" href="{{ $offer->redirect_url }}">View offer</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
