@php
    $allowed = \App\Models\HomeSection::allowedPartialKeys();
@endphp
@if(isset($homeSections) && $homeSections->isNotEmpty())
    @foreach($homeSections as $section)
        @if($section->is_active && in_array($section->partial_key, $allowed, true) && view()->exists('partials.home-sections.'.$section->partial_key))
            @include('partials.home-sections.'.$section->partial_key)
        @endif
    @endforeach
@else
    @include('partials.home-featured-static')
@endif
