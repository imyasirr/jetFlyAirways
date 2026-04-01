@if(isset($banners) && $banners->isNotEmpty())
@php
    $displayBanners = $banners->filter(fn ($b) => (bool) \App\Support\PublicImageStorage::url($b->image))->values();
@endphp
@if($displayBanners->isNotEmpty())
@php $bannerCount = $displayBanners->count(); @endphp
<section class="home-banner-ribbon" aria-label="{{ __('jetfly.banner_aria') }}">
    <div class="home-banner-shell">
        <div class="home-banner-viewport" id="homeBannerViewport">
            <div class="home-banner-track-inner" id="homeBannerTrack">
                @foreach($displayBanners as $b)
                    @php $bannerSrc = \App\Support\PublicImageStorage::url($b->image); @endphp
                    <article class="home-banner-pane">
                        <a href="{{ $b->link ?: '#' }}" class="home-banner-link" @if(!$b->link) onclick="return false;" aria-disabled="true" @endif>
                            <img
                                src="{{ $bannerSrc }}"
                                alt="{{ $b->title ?: __('jetfly.banner_promo') }}"
                                class="home-banner-img"
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                decoding="async"
                            >
                            @if($b->title)
                                <div class="home-banner-overlay">
                                    <span class="home-banner-title">{{ $b->title }}</span>
                                </div>
                            @endif
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
        @if($bannerCount > 1)
            <div class="home-banner-dots" role="tablist" aria-label="{{ __('jetfly.banner_slides') }}">
                @foreach($displayBanners as $b)
                    <button type="button" class="home-banner-dot {{ $loop->first ? 'is-active' : '' }}" data-slide="{{ $loop->index }}" aria-label="{{ __('jetfly.slide') }} {{ $loop->iteration }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
        @endif
    </div>
</section>
@if($bannerCount > 1)
<script>
(function () {
    var track = document.getElementById('homeBannerTrack');
    if (!track) return;
    var dots = document.querySelectorAll('.home-banner-dot');
    var panes = track.querySelectorAll('.home-banner-pane');
    function setActive(i) {
        dots.forEach(function (d, j) {
            d.classList.toggle('is-active', j === i);
            d.setAttribute('aria-selected', j === i ? 'true' : 'false');
        });
    }
    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () {
            var el = panes[i];
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
            setActive(i);
        });
    });
    track.addEventListener('scroll', function () {
        var w = track.offsetWidth;
        if (w < 1) return;
        var idx = Math.round(track.scrollLeft / w);
        if (idx >= 0 && idx < dots.length) setActive(idx);
    }, { passive: true });
})();
</script>
@endif
@endif
@endif
