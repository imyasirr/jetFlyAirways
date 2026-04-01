@extends('layouts.account')

@section('title', 'Booking '.$booking->booking_code)

@section('heading', 'E-ticket & details')

@section('content')
    @php
        $serviceLabel = match ($booking->module) {
            'flights' => 'Flight',
            'hotels' => 'Hotel',
            'packages' => 'Holiday package',
            'buses' => 'Bus',
            'trains' => 'Train',
            'cabs' => 'Cab',
            default => ucfirst($booking->module),
        };
    @endphp

    <div class="acct-card ticket">
        <p style="margin:0 0 4px;font-size:12px;text-transform:uppercase;letter-spacing:.12em;color:var(--acct-muted);">Electronic travel document</p>
        <p class="ticket-code">{{ $booking->booking_code }}</p>
        <p style="margin:8px 0 0;"><span class="badge">{{ $booking->status }}</span> <span class="badge" style="background:#fff7ed;color:#c2410c;">{{ $booking->payment_status }}</span></p>
        <hr style="border:none;border-top:1px dashed var(--acct-border);margin:20px 0;">
        <p><strong>{{ $serviceLabel }}</strong> · Item ID #{{ $booking->module_item_id }}</p>
        <p><strong>Travel date:</strong> {{ $booking->travel_date->format('l, d F Y') }}</p>
        <p><strong>Travellers:</strong> {{ $booking->travellers_count }}</p>
        <p><strong>Total:</strong> ₹{{ number_format((float) $booking->total_amount, 2) }}</p>
        @if($booking->notes)
            <p><strong>Notes:</strong> {{ $booking->notes }}</p>
        @endif
        <p style="font-size:13px;color:var(--acct-muted);margin-top:16px;">Present this code at check-in or share with our support team.</p>
        @if(!empty($ticketPdfUrl))
            <p style="margin-top:14px;"><a class="btn" href="{{ $ticketPdfUrl }}">Download ticket (PDF)</a></p>
        @elseif($booking->payment_status !== 'paid')
            <p style="font-size:13px;color:var(--acct-muted);margin-top:12px;">Pay online from your booking confirmation email/link to unlock the PDF ticket.</p>
        @endif
    </div>

    <p><a href="{{ route('account.bookings.index') }}" class="btn outline">← All bookings</a></p>
@endsection
