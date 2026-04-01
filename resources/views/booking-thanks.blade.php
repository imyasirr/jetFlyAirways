@extends('layouts.app')

@section('title', 'Payment received — '.$booking->booking_code)

@section('content')
    <div class="card" style="max-width:640px;">
        <h1 class="section-title">Payment successful</h1>
        @if(session('status'))
            <p style="color:#047857;font-weight:600;">{{ session('status') }}</p>
        @else
            <p style="color:#047857;font-weight:600;">Thank you — your payment for booking <strong>{{ $booking->booking_code }}</strong> is confirmed.</p>
        @endif
        <p><strong>Amount paid:</strong> ₹{{ number_format((float) $booking->total_amount, 2) }}</p>
        @if($ticketPdfUrl)
            <p style="margin-top:16px;"><a class="btn" href="{{ $ticketPdfUrl }}">Download ticket (PDF)</a></p>
        @endif
        <p style="margin-top:16px;display:flex;flex-wrap:wrap;gap:10px;">
            <a class="btn secondary" href="{{ route('home') }}">Home</a>
            @auth
                @if(auth()->id() === $booking->user_id)
                    <a class="btn secondary" href="{{ route('account.bookings.show', $booking) }}">My booking</a>
                @endif
            @endauth
        </p>
    </div>
@endsection
