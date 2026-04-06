@extends('layouts.admin')

@section('title', 'Booking reports')

@section('content')
    <div class="card">
        <h1 class="section-title">Booking reports</h1>
        <div class="row" style="align-items:flex-start;">
            <div class="admin-card" style="flex:1;min-width:280px;">
                <h2 style="margin:0 0 10px;">By module</h2>
                @foreach($byModule as $row)
                    <p style="margin:0 0 8px;"><strong>{{ ucfirst($row->module) }}</strong>: {{ $row->total }}</p>
                @endforeach
            </div>
            <div class="admin-card" style="flex:1;min-width:280px;">
                <h2 style="margin:0 0 10px;">By status</h2>
                @foreach($byStatus as $row)
                    <p style="margin:0 0 8px;"><strong>{{ ucfirst($row->status) }}</strong>: {{ $row->total }}</p>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2 style="margin:0 0 10px;">Latest bookings</h2>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Module</th>
                        <th>Travel date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latest as $row)
                        <tr>
                            <td><a href="{{ route('admin.bookings.show', $row) }}">{{ $row->booking_code }}</a></td>
                            <td>{{ $row->module }}</td>
                            <td>{{ $row->travel_date?->format('d M Y') }}</td>
                            <td>₹{{ number_format((float) $row->total_amount, 2) }}</td>
                            <td>{{ $row->status }}</td>
                            <td>{{ $row->payment_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

