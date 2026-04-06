@extends('layouts.account')

@section('title', 'Refund tracking')

@section('heading', 'Refund tracking')

@section('content')
    <div class="acct-card">
        @if($refundBookings->isEmpty())
            <p style="color:var(--acct-muted);margin:0;">No refund-related bookings found.</p>
        @else
            <table class="acct-table">
                <thead>
                    <tr>
                        <th>Booking code</th>
                        <th>Travel date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Refund status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refundBookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_code }}</strong></td>
                            <td>{{ $booking->travel_date->format('d M Y') }}</td>
                            <td>₹{{ number_format((float) $booking->total_amount, 2) }}</td>
                            <td>{{ $booking->status }}</td>
                            <td>{{ $booking->payment_status }}</td>
                            <td><a href="{{ route('account.bookings.show', $booking) }}" class="btn" style="padding:6px 12px;font-size:13px;">Open</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $refundBookings->links() }}</div>
        @endif
    </div>
@endsection

