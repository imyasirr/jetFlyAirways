@extends('layouts.app')

@section('content')
    <div class="card">
        <h1 class="section-title">{{ $module['title'] }} — Details</h1>
        <h3>{{ $item['title'] }}</h3>
        @if(!empty($item['meta']))
            <p style="color:#334155;">{{ $item['meta'] }}</p>
        @endif
        <p>{{ $item['description'] }}</p>
        <p><strong>Fare: Rs {{ number_format($item['price'], 2) }}</strong> <span style="font-size:14px;color:#64748b;">(per unit × travellers at checkout)</span></p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:12px;">
            <a class="btn" href="{{ route('booking.form', ['module' => $slug, 'id' => $item['id']]) }}">Book Now</a>
            <a class="btn secondary" href="{{ route('module.index', $slug) }}">Back to list</a>
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
    </div>
@endsection
