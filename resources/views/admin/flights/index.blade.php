@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Manage Flights</h1>
            <a class="btn" href="{{ route('admin.flights.create') }}">Add Flight</a>
        </div>

        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif

        <div style="overflow:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Flight</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Route</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Departure</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Price</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $flight)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $flight->airline }} ({{ $flight->flight_number }})</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $flight->from_city }} → {{ $flight->to_city }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $flight->departure_at }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">Rs {{ number_format($flight->price, 2) }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.flights.edit', $flight) }}">Edit</a>
                                <form method="post" action="{{ route('admin.flights.destroy', $flight) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:10px;">No flights added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ $flights->links() }}</div>
    </div>
@endsection
