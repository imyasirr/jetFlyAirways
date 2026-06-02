@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('content')
    <div class="jf-ota-detail">
        <nav class="site-breadcrumbs jf-ota-detail__crumb" aria-label="Breadcrumb">
            <ol>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('module.index', $slug) }}">{{ $module['title'] }}</a></li>
                <li><span aria-current="page">{{ \Illuminate\Support\Str::limit($item['title'], 56) }}</span></li>
            </ol>
        </nav>

        <article class="jf-ota-detail__card">
            <header class="jf-ota-detail__head">
                <p class="jf-ota-detail__eyebrow">{{ $module['title'] }}</p>
                <h1 class="jf-ota-detail__title">{{ $item['title'] }}</h1>
                @if(!empty($item['meta']))
                    <p class="jf-ota-detail__meta">{{ $item['meta'] }}</p>
                @endif
            </header>
            <div class="jf-ota-detail__body">
                <p class="jf-ota-detail__desc">{{ $item['description'] }}</p>
                <div class="jf-ota-detail__fare">
                    <span class="jf-ota-detail__fare-label">From</span>
                    <span class="jf-ota-detail__fare-amount">₹{{ number_format($item['price'], 2) }}</span>
                    <span class="jf-ota-detail__fare-note">per unit · taxes &amp; fees at checkout</span>
                </div>
            </div>
            <div class="jf-ota-detail__actions">
            <a class="btn jf-ota-detail__cta" href="{{ route('booking.form', ['module' => $slug, 'item' => $item['slug']]) }}">Book now</a>
            <a class="btn secondary" href="{{ route('module.index', $slug) }}">Back to results</a>
            @auth
                @if($inWishlist ?? false)
                    <form method="post" action="{{ route('wishlist.destroy', ['module' => $slug, 'id' => $item['id']]) }}" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn secondary" style="cursor:pointer;">Remove from wishlist</button>
                    </form>
                @else
                    <form method="post" action="{{ route('wishlist.store', ['module' => $slug, 'id' => $item['id']]) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn secondary" style="cursor:pointer;">Save to wishlist</button>
                    </form>
                @endif
            @endauth
            </div>
        </article>
    </div>
@endsection
