@extends('layouts.account')

@section('title', 'My bookings')

@section('heading', 'My bookings')

@section('content')
    <div class="acct-card">
        @if($bookings->isEmpty())
            <p style="color:var(--acct-muted);">You have no bookings linked to this account yet. Book while logged in, or contact support with your booking code.</p>
            <a href="{{ route('home') }}" class="btn" style="margin-top:12px;">Start a search</a>
        @else
            <table class="acct-table">
                <thead>
                    <tr>
                        <th>Booking code</th>
                        <th>Service</th>
                        <th>Travel date</th>
                        <th>Guests</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                        <tr>
                            <td><strong>{{ $b->booking_code }}</strong></td>
                            <td>{{ $b->module }}</td>
                            <td>{{ $b->travel_date->format('d M Y') }}</td>
                            <td>{{ $b->travellers_count }}</td>
                            <td>₹{{ number_format((float) $b->total_amount, 2) }}</td>
                            <td><span class="badge">{{ $b->status }}</span></td>
                            <td>{{ $b->payment_status }}</td>
                            <td style="display:flex;gap:8px;flex-wrap:wrap;">
                                <a href="{{ route('account.bookings.show', $b) }}" class="btn" style="padding:6px 12px;font-size:13px;">Ticket</a>
                                @if($b->status !== 'cancelled' && $b->travel_date->isFuture())
                                    <form method="post" action="{{ route('account.bookings.cancel', $b) }}">
                                        @csrf
                                        <button type="submit" class="btn outline" style="padding:6px 12px;font-size:13px;">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $bookings->links() }}</div>
        @endif
    </div>
@endsection
