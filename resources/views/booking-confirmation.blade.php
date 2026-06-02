@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('title', 'Booking confirmed — '.$bookingCode)

@section('content')
    <div class="booking-shell">
        <header class="booking-page-head">
            <nav class="site-breadcrumbs" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('module.index', $slug) }}">{{ $module['title'] ?? ucfirst($slug) }}</a></li>
                    <li><a href="{{ route('module.show', ['module' => $slug, 'item' => $item['slug']]) }}">{{ \Illuminate\Support\Str::limit($item['title'], 48) }}</a></li>
                    <li><span aria-current="page">{{ __('Confirmed') }}</span></li>
                </ol>
            </nav>
        </header>

        <article class="card booking-card">
            <p class="booking-hero-kicker">{{ $module['title'] ?? ucfirst($slug) }}</p>
            <div class="booking-status-pill booking-status-pill--success" role="status">
                <span aria-hidden="true">✓</span> {{ __('Booking received') }}
            </div>
            <h1 class="booking-hero-title">{{ __('Booking confirmed') }}</h1>
            <p class="booking-card__intro">
                {{ __('We have saved your reservation.') }} <strong>{{ $bookingCode }}</strong>
                {{ __('is your reference for emails, payment, and support.') }}
            </p>

            <div class="fare-panel">
                <p class="fare-panel__label">{{ __('Fare summary') }}</p>
                @if(($discountAmount ?? 0) > 0)
                    <div class="fare-panel__row">
                        <span>{{ __('Subtotal') }}</span>
                        <span>₹{{ number_format($subtotalAmount ?? $totalAmount, 2) }}</span>
                    </div>
                    <div class="fare-panel__row fare-panel__row--discount">
                        <span>{{ __('Coupon discount') }}</span>
                        <span>−₹{{ number_format($discountAmount, 2) }}</span>
                    </div>
                @endif
                <div class="fare-panel__total">
                    {{ __('Amount to pay') }}: ₹{{ number_format($totalAmount, 2) }}
                    <span class="fare-panel__total-note"> — {{ __('pending payment') }}</span>
                </div>
            </div>

            <dl class="booking-dl">
                <div class="booking-dl__row">
                    <dt>{{ __('Service') }}</dt>
                    <dd>{{ $item['title'] }}</dd>
                </div>
                <div class="booking-dl__row">
                    <dt>{{ __('Passenger') }}</dt>
                    <dd>{{ $data['name'] }} · {{ $data['email'] }}</dd>
                </div>
                <div class="booking-dl__row">
                    <dt>{{ __('Travel date') }}</dt>
                    <dd>{{ $data['travel_date'] }}</dd>
                </div>
                <div class="booking-dl__row">
                    <dt>{{ __('Travellers') }}</dt>
                    <dd>{{ $data['travellers'] }}</dd>
                </div>
            </dl>

            @if(!empty($paymentCheckoutUrl))
                <p class="booking-hint">{{ __('Complete secure payment to confirm — you will get a downloadable ticket (PDF) after successful payment.') }}</p>
                <p style="margin:0 0 18px;">
                    <a class="btn checkout-pay-btn" href="{{ $paymentCheckoutUrl }}">{{ __('Pay now with Razorpay') }}</a>
                </p>
            @else
                <p class="booking-hint">
                    {{ __('Your booking is saved. Configure') }} <code>RAZORPAY_KEY</code> / <code>RAZORPAY_SECRET</code>
                    {{ __('in') }} <code>.env</code> {{ __('to enable online payment and PDF tickets.') }}
                </p>
            @endif

            <div class="booking-actions">
                <a class="btn secondary" href="{{ route('home') }}">{{ __('Back to homepage') }}</a>
                @auth
                    @isset($booking)
                        @if(auth()->id() === $booking->user_id)
                            <a class="btn secondary" href="{{ route('account.bookings.show', $booking) }}">{{ __('View in My account') }}</a>
                        @endif
                    @endisset
                @endauth
            </div>
        </article>
    </div>
@endsection
