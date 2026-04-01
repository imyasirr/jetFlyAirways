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

        <div style="overflow:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Hotel</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Location</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Status</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Price/Night</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotels as $hotel)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $hotel->name }} ({{ $hotel->star_rating }}⭐)</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $hotel->city }} - {{ $hotel->location }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">Active: {{ $hotel->is_active ? 'Yes' : 'No' }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">Rs {{ number_format($hotel->price_per_night, 2) }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.hotels.edit', $hotel) }}">Edit</a>
                                <form method="post" action="{{ route('admin.hotels.destroy', $hotel) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </td>
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
