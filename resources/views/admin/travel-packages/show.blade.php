@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">{{ $package->name }}</h1>
        <p><strong>Category:</strong> {{ $package->category }} | <strong>Destination:</strong> {{ $package->destination }}</p>
        <p><strong>Duration:</strong> {{ $package->duration_days }} days</p>
        <p><strong>Price:</strong> Rs {{ number_format($package->price, 2) }} @if($package->offer_price) | <strong>Offer:</strong> Rs {{ number_format($package->offer_price, 2) }} @endif</p>
        <p><strong>Published:</strong> {{ $package->is_published ? 'Yes' : 'No' }}</p>
        <a class="btn secondary" href="{{ route('admin.travel-packages.index') }}">Back</a>
    </div>
@endsection
