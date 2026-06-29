@php
    $destinationCards = isset($popularDestinations) && $popularDestinations->isNotEmpty()
        ? $popularDestinations
        : collect($topDestinations ?? [])->map(fn ($name) => (object) [
            'name' => $name,
            'slug' => null,
            'tag_line' => null,
            'banner_url' => null,
            'package_destination' => $name,
        ]);
@endphp

@if($destinationCards->isNotEmpty())
<section id="popular-destinations" class="jfa-section jfa-section--muted">
    <div class="jfa-container">
        <div class="jfa-section-head">
            <div>
                <h2 class="jfa-section-title">Popular Destinations</h2>
                <p class="jfa-section-sub">Trending this season</p>
            </div>
        </div>
        <div class="jfa-grid jfa-grid--6">
            @foreach($destinationCards as $destination)
                @php
                    $href = filled($destination->slug ?? null)
                        ? route('destinations.show', $destination->slug)
                        : route('module.index', 'packages').'?destination='.urlencode($destination->package_destination ?? $destination->name);
                    $image = $destination instanceof \App\Models\PopularDestination
                        ? ($destination->bannerUrl() ?: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&q=80&w=400')
                        : ($destination->banner_url ?? 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&q=80&w=400');
                    $subtitle = $destination->tag_line ?? 'Explore packages';
                @endphp
                <a href="{{ $href }}" class="jfa-dest">
                    <img src="{{ $image }}" alt="{{ $destination->name }}" loading="lazy">
                    <span class="jfa-dest__overlay"></span>
                    <span class="jfa-dest__text">
                        <span class="jfa-dest__name">{{ $destination->name }}</span>
                        <span class="jfa-dest__price">{{ $subtitle }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
