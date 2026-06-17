@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('content')
    <div class="jfa-page-head">
        <nav class="jfa-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <a href="{{ route('module.index', $slug) }}">{{ $module['title'] }}</a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span aria-current="page">{{ \Illuminate\Support\Str::limit($item['title'], 56) }}</span>
        </nav>
    </div>

    <article class="jfa-card" style="padding:32px;">
        <p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--jfa-muted);">{{ $module['title'] }}</p>
        <h1 style="margin:0 0 8px;font-size:clamp(1.5rem,3vw,2rem);">{{ $item['title'] }}</h1>
        @if(!empty($item['meta']))
            <p style="margin:0 0 20px;color:var(--jfa-muted);">{{ $item['meta'] }}</p>
        @endif
        <p style="margin:0 0 24px;line-height:1.7;color:var(--jfa-on-surface-variant,#414754);">{{ $item['description'] }}</p>

        <div style="padding:20px;border-radius:14px;background:var(--jfa-surface-low);margin-bottom:24px;">
            <span style="font-size:12px;color:var(--jfa-muted);">From</span>
            <div style="font-family:Montserrat,sans-serif;font-size:2rem;font-weight:800;color:var(--jfa-booking-blue);">₹{{ number_format($item['price'], 2) }}</div>
            <span style="font-size:13px;color:var(--jfa-muted);">per unit · taxes &amp; fees at checkout</span>
        </div>

        <div class="form-actions">
            <a class="btn" href="{{ route('booking.form', ['module' => $slug, 'item' => $item['slug']]) }}">Book now</a>
            <a class="btn secondary" href="{{ route('module.index', $slug) }}">Back to results</a>
            @auth
                @if($inWishlist ?? false)
                    <form method="post" action="{{ route('wishlist.destroy', ['module' => $slug, 'id' => $item['id']]) }}" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn secondary">Remove from wishlist</button>
                    </form>
                @else
                    <form method="post" action="{{ route('wishlist.store', ['module' => $slug, 'id' => $item['id']]) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn secondary">Save to wishlist</button>
                    </form>
                @endif
            @endauth
        </div>
    </article>
@endsection
