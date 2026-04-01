@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">{{ $route->operator_name }}</h1>
        <p><strong>Route:</strong> {{ $route->from_city }} → {{ $route->to_city }}</p>
        <p><strong>Departure:</strong> {{ $route->departure_at }} | <strong>Arrival:</strong> {{ $route->arrival_at }}</p>
        <p><strong>Price:</strong> Rs {{ number_format($route->price, 2) }} | <strong>Seats:</strong> {{ $route->seats_available }}</p>
        <a class="btn secondary" href="{{ route('admin.bus-routes.index') }}">Back</a>
    </div>
@endsection
