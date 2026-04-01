@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">{{ $service->service_type }}</h1>
        <p><strong>From:</strong> {{ $service->from_location }} → {{ $service->to_location ?? '—' }}</p>
        <p><strong>Base Fare:</strong> Rs {{ number_format($service->base_fare, 2) }}</p>
        <p><strong>Per KM:</strong> {{ $service->per_km_rate !== null ? number_format($service->per_km_rate, 2) : '—' }}</p>
        <a class="btn secondary" href="{{ route('admin.cab-services.index') }}">Back</a>
    </div>
@endsection
