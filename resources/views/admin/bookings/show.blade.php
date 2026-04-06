@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:640px;">
        <h1 class="section-title">Booking {{ $booking->booking_code }}</h1>
        <p><strong>Service:</strong> {{ $itemTitle }}</p>
        <p><strong>Module:</strong> {{ $booking->module }} · ID {{ $booking->module_item_id }}</p>
        <p><strong>Travel date:</strong> {{ $booking->travel_date?->format('d M Y') }}</p>
        <p><strong>Travellers:</strong> {{ $booking->travellers_count }}</p>
        <p><strong>Total:</strong> Rs {{ number_format($booking->total_amount, 2) }}</p>
        <p><strong>Status:</strong> {{ $booking->status }} · <strong>Payment:</strong> {{ $booking->payment_status }}</p>
        @if($booking->provider_service)
            <p><strong>Provider:</strong> {{ $booking->provider_service }}</p>
        @endif
        @if($booking->provider_sync_status)
            <p><strong>Provider sync:</strong> {{ $booking->provider_sync_status }}</p>
        @endif
        @if($booking->notes)
            <p><strong>Notes:</strong> {{ $booking->notes }}</p>
        @endif
        <p style="font-size:13px;color:#64748b;">Created {{ $booking->created_at?->format('d M Y H:i') }}</p>
        <a class="btn secondary" href="{{ route('admin.bookings.index') }}">All bookings</a>
    </div>
@endsection
