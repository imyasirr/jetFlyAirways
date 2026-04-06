@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h2 class="section-title" style="margin:0;font-size:1.1rem;">All flights</h2>
            <a class="btn" href="{{ route('admin.flights.create') }}">Add flight</a>
        </div>

        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Flight</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $flight)
                        <tr>
                            <td>{{ $flight->airline }} ({{ $flight->flight_number }})</td>
                            <td>{{ $flight->from_city }} → {{ $flight->to_city }}</td>
                            <td>{{ $flight->departure_at }}</td>
                            <td>Rs {{ number_format($flight->price, 2) }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.flights.edit', $flight) }}">Edit</a>
                                <form method="post" action="{{ route('admin.flights.destroy', $flight) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No flights added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ $flights->links() }}</div>
    </div>
@endsection

