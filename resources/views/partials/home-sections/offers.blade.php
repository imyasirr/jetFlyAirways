@if(isset($offers) && $offers->isNotEmpty())
<section class="jfa-section jfa-section--white">
    <div class="jfa-container">
        <div class="jfa-section-head">
            <div>
                <h2 class="jfa-section-title">Exclusive Offers</h2>
                <p class="jfa-section-sub">Limited-time deals just for you</p>
            </div>
        </div>
        <div class="jfa-grid jfa-grid--3">
            @foreach($offers as $offer)
                <article class="jfa-listing-card">
                    <div style="padding:16px 20px;background:var(--jfa-booking-blue);color:#fff;">
                        <div style="font-family:Montserrat,sans-serif;font-weight:800;font-size:1.25rem;">{{ $offer->title }}</div>
                    </div>
                    <div class="jfa-listing-card__body">
                        @if($offer->description)
                            <p class="jfa-listing-card__sub">{{ \Illuminate\Support\Str::limit(strip_tags($offer->description), 120) }}</p>
                        @endif
                        @if($offer->redirect_url)
                            <a class="btn" href="{{ $offer->redirect_url }}" style="width:100%;margin-top:8px;">Grab deal</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
