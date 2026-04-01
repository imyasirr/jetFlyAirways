@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Flight Details</h1>
        <p><strong>Airline:</strong> {{ $flight->airline }}</p>
        <p><strong>Flight Number:</strong> {{ $flight->flight_number }}</p>
        <p><strong>Route:</strong> {{ $flight->from_city }} → {{ $flight->to_city }}</p>
        <p><strong>Departure:</strong> {{ $flight->departure_at }}</p>
        <p><strong>Arrival:</strong> {{ $flight->arrival_at }}</p>
        <p><strong>Price:</strong> Rs {{ number_format($flight->price, 2) }}</p>
        <a class="btn secondary" href="{{ route('admin.flights.index') }}">Back</a>
    </div>
@endsection
