@extends('layouts.admin')

@section('content')
    @if(session('status'))
        <div class="flx-flash">
            <span class="material-symbols-outlined" aria-hidden="true">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    <div class="flx-stats">
        <div class="flx-stat">
            <span class="flx-stat-icon flx-stat-icon--blue"><span class="material-symbols-outlined" aria-hidden="true">flight</span></span>
            <span class="flx-stat-body">
                <strong>{{ $stats['total'] }}</strong>
                <small>Total flights</small>
            </span>
        </div>
        <div class="flx-stat">
            <span class="flx-stat-icon flx-stat-icon--green"><span class="material-symbols-outlined" aria-hidden="true">flight_takeoff</span></span>
            <span class="flx-stat-body">
                <strong>{{ $stats['active'] }}</strong>
                <small>Active</small>
            </span>
        </div>
        <div class="flx-stat">
            <span class="flx-stat-icon flx-stat-icon--red"><span class="material-symbols-outlined" aria-hidden="true">flight_land</span></span>
            <span class="flx-stat-body">
                <strong>{{ $stats['inactive'] }}</strong>
                <small>Inactive</small>
            </span>
        </div>
        <div class="flx-stat">
            <span class="flx-stat-icon flx-stat-icon--gold"><span class="material-symbols-outlined" aria-hidden="true">schedule</span></span>
            <span class="flx-stat-body">
                <strong>{{ $stats['upcoming'] }}</strong>
                <small>Upcoming departures</small>
            </span>
        </div>
    </div>

    <div class="card flx-card">
        <div class="flx-toolbar">
            <div class="flx-toolbar-left">
                <h2 class="section-title flx-title">All flights</h2>
                <span class="flx-count">{{ $flights->count() }} result{{ $flights->count() === 1 ? '' : 's' }}</span>
            </div>
            <form method="get" action="{{ route('admin.flights.index') }}" class="flx-filters" data-no-loader>
                <label class="flx-search">
                    <span class="material-symbols-outlined" aria-hidden="true">search</span>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search airline, number, city…">
                </label>
                <select name="status" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn secondary">Filter</button>
                @if(request('q') || request('status'))
                    <a href="{{ route('admin.flights.index') }}" class="flx-clear" title="Clear filters">
                        <span class="material-symbols-outlined" aria-hidden="true">close</span>
                    </a>
                @endif
            </form>
            <a class="btn flx-add" href="{{ route('admin.flights.create') }}">
                <span class="material-symbols-outlined" aria-hidden="true">add</span>
                Add flight
            </a>
        </div>

        <div class="admin-table-scroll">
            <table class="admin-table flx-table">
                <thead>
                    <tr>
                        <th>Flight</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Cabin</th>
                        <th>Seats</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $flight)
                        @php
                            $initials = collect(explode(' ', (string) $flight->airline))
                                ->filter()->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
                            $isPast = $flight->departure_at && $flight->departure_at->isPast();
                        @endphp
                        <tr>
                            <td>
                                <span class="flx-flight">
                                    <span class="flx-avatar">{{ $initials ?: 'FL' }}</span>
                                    <span class="flx-flight-text">
                                        <strong>{{ $flight->airline }}</strong>
                                        <small>{{ $flight->flight_number }}</small>
                                    </span>
                                </span>
                            </td>
                            <td>
                                <span class="flx-route">
                                    <span>{{ $flight->from_city }}</span>
                                    <span class="flx-route-icon">
                                        <span class="flx-route-line"></span>
                                        <span class="material-symbols-outlined" aria-hidden="true">flight</span>
                                        <span class="flx-route-line"></span>
                                    </span>
                                    <span>{{ $flight->to_city }}</span>
                                </span>
                                @if(!is_null($flight->stops))
                                    <small class="flx-muted">{{ $flight->stops == 0 ? 'Non-stop' : $flight->stops . ' stop' . ($flight->stops > 1 ? 's' : '') }}</small>
                                @endif
                            </td>
                            <td>
                                @if($flight->departure_at)
                                    <span class="flx-depart {{ $isPast ? 'is-past' : '' }}">
                                        <strong>{{ $flight->departure_at->format('d M Y') }}</strong>
                                        <small>{{ $flight->departure_at->format('h:i A') }} · {{ $isPast ? 'Departed' : $flight->departure_at->diffForHumans() }}</small>
                                    </span>
                                @else
                                    <span class="flx-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($flight->cabin_class)
                                    <span class="flx-chip">{{ ucfirst($flight->cabin_class) }}</span>
                                @else
                                    <span class="flx-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="flx-seats {{ ($flight->seats_available ?? 0) <= 10 ? 'is-low' : '' }}">
                                    {{ $flight->seats_available ?? '—' }}
                                </span>
                            </td>
                            <td><span class="flx-price">₹{{ number_format($flight->price, 0) }}</span></td>
                            <td>
                                <span class="flx-badge {{ $flight->is_active ? 'flx-badge--active' : 'flx-badge--inactive' }}">
                                    <span class="flx-badge-dot"></span>
                                    {{ $flight->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.flights.edit', $flight),
                                    'delete' => route('admin.flights.destroy', $flight),
                                    'deleteConfirm' => 'Delete this flight?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="flx-empty">
                                    <span class="material-symbols-outlined" aria-hidden="true">travel</span>
                                    <strong>No flights found</strong>
                                    @if(request('q') || request('status'))
                                        <p>Try changing your search or <a href="{{ route('admin.flights.index') }}">clear filters</a>.</p>
                                    @else
                                        <p>Add your first flight to get started.</p>
                                        <a class="btn" href="{{ route('admin.flights.create') }}">Add flight</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
@endsection
