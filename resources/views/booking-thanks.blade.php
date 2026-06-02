@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('title', 'Payment received — '.$booking->booking_code)

@section('content')
    <div class="booking-shell">
        <header class="booking-page-head">
            <nav class="site-breadcrumbs" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('module.index', ['module' => $booking->module]) }}">{{ ucfirst($booking->module) }}</a></li>
                    <li><span aria-current="page">{{ __('Payment complete') }}</span></li>
                </ol>
            </nav>
        </header>

        <article class="card booking-card">
            <div class="booking-status-pill booking-status-pill--success" role="status">
                <span aria-hidden="true">✓</span> {{ __('Payment successful') }}
            </div>
            <h1 class="booking-hero-title">{{ __('Thank you') }}</h1>
            @if(session('status'))
                <p class="booking-card__intro" style="color:#047857;font-weight:600;">{{ session('status') }}</p>
            @else
                <p class="booking-card__intro">
                    {{ __('Your payment for booking') }} <strong>{{ $booking->booking_code }}</strong> {{ __('is confirmed.') }}
                </p>
            @endif

            <div class="fare-panel">
                <p class="fare-panel__label">{{ __('Payment details') }}</p>
                <div class="fare-panel__total" style="border-top:none;margin-top:0;padding-top:0;">
                    {{ __('Amount paid') }}: ₹{{ number_format((float) $booking->total_amount, 2) }}
                </div>
            </div>

            @if($ticketPdfUrl)
                <div class="booking-actions" style="border-top:none;margin-top:0;padding-top:8px;">
                    <a class="btn checkout-pay-btn" href="{{ $ticketPdfUrl }}">{{ __('Download ticket (PDF)') }}</a>
                </div>
            @endif

            <div class="booking-actions">
                <a class="btn secondary" href="{{ route('home') }}">{{ __('Back to homepage') }}</a>
                @auth
                    @if(auth()->id() === $booking->user_id)
                        <a class="btn secondary" href="{{ route('account.bookings.show', $booking) }}">{{ __('My booking') }}</a>
                    @endif
                @endauth
            </div>
        </article>
    </div>
@endsection
