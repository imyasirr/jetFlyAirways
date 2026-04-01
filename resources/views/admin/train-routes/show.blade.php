@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">{{ $route->train_name }}</h1>
        <p><strong>Number:</strong> {{ $route->train_number }}</p>
        <p><strong>Route:</strong> {{ $route->from_city }} → {{ $route->to_city }}</p>
        <p><strong>Departure:</strong> {{ $route->departure_at }} | <strong>Arrival:</strong> {{ $route->arrival_at }}</p>
        <p><strong>Price:</strong> Rs {{ number_format($route->price, 2) }}</p>
        <a class="btn secondary" href="{{ route('admin.train-routes.index') }}">Back</a>
    </div>
@endsection
