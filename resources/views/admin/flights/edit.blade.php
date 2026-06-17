@extends('layouts.admin')

@section('content')
    @php
        $initials = collect(explode(' ', (string) $flight->airline))
            ->filter()->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
    @endphp
    <div class="flxf-head">
        <a href="{{ route('admin.flights.index') }}" class="flx-iconbtn" title="Back to flights">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        </a>
        <span class="flx-avatar">{{ $initials ?: 'FL' }}</span>
        <div class="flxf-head-text">
            <h1 class="section-title flxf-head-title">{{ $flight->airline }} <small>{{ $flight->flight_number }}</small></h1>
            <p class="flxf-head-sub">
                {{ $flight->from_city }} → {{ $flight->to_city }}
                @if($flight->departure_at) · {{ $flight->departure_at->format('d M Y, h:i A') }} @endif
            </p>
        </div>
        <span class="flx-badge {{ $flight->is_active ? 'flx-badge--active' : 'flx-badge--inactive' }}">
            <span class="flx-badge-dot"></span>
            {{ $flight->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <div class="card flxf-card">
        <form method="post" action="{{ route('admin.flights.update', $flight) }}">
            @method('PUT')
            @include('admin.flights._form')
        </form>
    </div>
@endsection
