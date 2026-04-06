@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Manage Hotels</h1>
            <a class="btn" href="{{ route('admin.hotels.create') }}">Add Hotel</a>
        </div>

        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Price/Night</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotels as $hotel)
                        <tr>
                            <td>{{ $hotel->name }} ({{ $hotel->star_rating }}★)</td>
                            <td>{{ $hotel->city }} - {{ $hotel->location }}</td>
                            <td>Active: {{ $hotel->is_active ? 'Yes' : 'No' }}</td>
                            <td>Rs {{ number_format($hotel->price_per_night, 2) }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.hotels.edit', $hotel) }}">Edit</a>
                                <form method="post" action="{{ route('admin.hotels.destroy', $hotel) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:10px;">No hotels added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ $hotels->links() }}</div>
    </div>
@endsection
