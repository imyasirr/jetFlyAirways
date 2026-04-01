@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Hotel Details</h1>
        <p><strong>Name:</strong> {{ $hotel->name }}</p>
        <p><strong>City:</strong> {{ $hotel->city }}</p>
        <p><strong>Location:</strong> {{ $hotel->location }}</p>
        <p><strong>Rating:</strong> {{ $hotel->star_rating }}⭐</p>
        <p><strong>Price:</strong> Rs {{ number_format($hotel->price_per_night, 2) }}</p>
        <p><strong>Amenities:</strong> {{ is_array($hotel->amenities) ? implode(', ', $hotel->amenities) : '-' }}</p>
        <a class="btn secondary" href="{{ route('admin.hotels.index') }}">Back</a>
    </div>
@endsection
