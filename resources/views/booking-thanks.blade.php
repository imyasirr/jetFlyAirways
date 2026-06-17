@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('title', 'Payment received — '.$booking->booking_code)

@section('content')
    <div class="jfa-booking-steps" style="margin-bottom:24px;">
        <span class="jfa-booking-step is-done">Search</span>
        <span class="jfa-booking-step is-done">Details</span>
        <span class="jfa-booking-step is-done">Book</span>
        <span class="jfa-booking-step is-done">Pay</span>
        <span class="jfa-booking-step is-active">Confirm</span>
    </div>

    <article class="jfa-card page-card page-card--narrow" style="margin:0 auto;text-align:center;">
        <span class="jfa-status-pill" style="margin-bottom:16px;"><span aria-hidden="true">✓</span> {{ __('Payment successful') }}</span>
        <h1 style="margin:0 0 12px;">{{ __('Thank you') }}</h1>
        <p style="color:var(--jfa-muted);margin:0 0 24px;">
            {{ __('Your payment for booking') }} <strong>{{ $booking->booking_code }}</strong> {{ __('is confirmed.') }}
        </p>
        <div class="jfa-checkout-amount">
            <p style="margin:0;font-size:13px;color:var(--jfa-muted);">{{ __('Amount paid') }}</p>
            <p class="jfa-checkout-amount__value">₹{{ number_format((float) $booking->total_amount, 2) }}</p>
        </div>
        @if($ticketPdfUrl)
            <div class="form-actions" style="justify-content:center;margin-top:24px;">
                <a class="btn" href="{{ $ticketPdfUrl }}">{{ __('Download ticket (PDF)') }}</a>
            </div>
        @endif
        <div class="form-actions" style="justify-content:center;">
            <a class="btn secondary" href="{{ route('home') }}">{{ __('Back to homepage') }}</a>
            @auth
                @if(auth()->id() === $booking->user_id)
                    <a class="btn secondary" href="{{ route('account.bookings.show', $booking) }}">{{ __('My booking') }}</a>
                @endif
            @endauth
        </div>
    </article>
@endsection
