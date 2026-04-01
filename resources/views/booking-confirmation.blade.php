@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:700px;">
        <h1 class="section-title">Booking Confirmed</h1>
        <p><strong>Booking Code:</strong> {{ $bookingCode }}</p>
        <p><strong>Service:</strong> {{ $item['title'] }}</p>
        <p><strong>Estimated total:</strong> Rs {{ number_format($totalAmount, 2) }} <span style="font-size:14px;color:#64748b;">(pending payment)</span></p>
        <p><strong>Passenger:</strong> {{ $data['name'] }} ({{ $data['email'] }})</p>
        <p><strong>Travel Date:</strong> {{ $data['travel_date'] }}</p>
        <p><strong>Travellers:</strong> {{ $data['travellers'] }}</p>
        @if(!empty($paymentCheckoutUrl))
            <p style="font-size:14px;color:#475569;">Complete payment to confirm — you will get a downloadable ticket (PDF) after successful payment.</p>
            <div style="margin-top:14px;">
                <a class="btn" href="{{ $paymentCheckoutUrl }}">Pay now (Razorpay)</a>
            </div>
        @else
            <p style="font-size:14px;color:#475569;">Your booking is saved. Configure <code>RAZORPAY_KEY</code> / <code>RAZORPAY_SECRET</code> in <code>.env</code> to enable online payment and PDF tickets.</p>
        @endif
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:16px;">
            <a class="btn secondary" href="{{ route('home') }}">Back to Homepage</a>
            @auth
                @isset($booking)
                    @if(auth()->id() === $booking->user_id)
                        <a class="btn secondary" href="{{ route('account.bookings.show', $booking) }}">View in My account</a>
                    @endif
                @endisset
            @endauth
        </div>
    </div>
@endsection
