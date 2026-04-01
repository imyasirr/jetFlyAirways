@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:700px;">
        <h1 class="section-title">Book {{ $module['title'] }}</h1>
        <p><strong>{{ $item['title'] }}</strong> — Rs {{ number_format($item['price'], 2) }} per unit</p>
        <form method="post" action="{{ route('booking.submit', ['module' => $slug, 'id' => $id]) }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div><label>Name</label><input name="name" required value="{{ old('name', auth()->user()?->name) }}"></div>
                <div><label>Email</label><input type="email" name="email" required value="{{ old('email', auth()->user()?->email) }}"></div>
                <div><label>Phone</label><input name="phone" required value="{{ old('phone', auth()->user()?->phone) }}"></div>
                <div><label>Travellers</label><input type="number" min="1" max="20" name="travellers" required value="{{ old('travellers', 1) }}"></div>
                <div><label>Travel Date</label><input type="date" name="travel_date" required value="{{ old('travel_date') }}"></div>
            </div>
            <div style="margin-top:10px;">
                <label>Special Notes</label>
                <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>
            <button class="btn" style="margin-top:12px;">Continue to Payment</button>
        </form>
    </div>
@endsection
