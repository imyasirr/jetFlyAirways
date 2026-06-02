@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('title', 'Pay — '.$booking->booking_code)

@section('content')
    <div class="booking-shell booking-shell--narrow">
        <header class="booking-page-head">
            <nav class="site-breadcrumbs" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('module.index', ['module' => $booking->module]) }}">{{ ucfirst($booking->module) }}</a></li>
                    <li><span aria-current="page">{{ __('Payment') }}</span></li>
                </ol>
            </nav>
        </header>

        <article class="card booking-card">
            <p class="booking-hero-kicker">{{ __('Secure checkout') }}</p>
            <h1 class="booking-hero-title">{{ __('Complete payment') }}</h1>
            <p class="booking-card__intro" style="margin-bottom: 6px;">
                {{ __('Booking') }} <strong>{{ $booking->booking_code }}</strong>
            </p>

            <div class="checkout-amount">
                <p class="checkout-amount__label">{{ __('Total payable') }}</p>
                <p class="checkout-amount__value">₹{{ number_format((float) $booking->total_amount, 2) }}</p>
                <p class="checkout-amount__code">{{ __('INR') }} · {{ __('Razorpay') }}</p>
            </div>

            @if((float) ($booking->discount_amount ?? 0) > 0)
                <p class="checkout-discount-note">
                    {{ __('Coupon applied:') }} −₹{{ number_format((float) $booking->discount_amount, 2) }}
                    @if((float) ($booking->subtotal_amount ?? 0) > 0)
                        ({{ __('subtotal') }} ₹{{ number_format((float) $booking->subtotal_amount, 2) }})
                    @endif
                </p>
            @endif

            <p class="checkout-trust">
                <span>{{ __('256-bit encryption') }}</span>
                <span>·</span>
                <span>{{ __('UPI · Cards · Netbanking') }}</span>
            </p>

            <form id="pay-form" method="post" action="{{ route('payments.verify') }}">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="razorpay_order_id" id="field_order_id" value="">
                <input type="hidden" name="razorpay_payment_id" id="field_payment_id" value="">
                <input type="hidden" name="razorpay_signature" id="field_signature" value="">
            </form>
            <button type="button" id="rzp-launch" class="btn checkout-pay-btn">{{ __('Pay securely with Razorpay') }}</button>
            <p class="checkout-footnote">{{ __('You will return to a confirmation page after a successful payment.') }}</p>
        </article>
    </div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-launch').addEventListener('click', function () {
    var options = {
        key: @json($razorpayKey),
        amount: {{ (int) $amountPaise }},
        currency: 'INR',
        name: 'Jet Fly Airways',
        description: 'Booking {{ $booking->booking_code }}',
        order_id: @json($razorpayOrderId),
        prefill: {
            name: @json($prefillName),
            email: @json($prefillEmail),
            contact: @json($prefillPhone)
        },
        handler: function (response) {
            document.getElementById('field_order_id').value = response.razorpay_order_id;
            document.getElementById('field_payment_id').value = response.razorpay_payment_id;
            document.getElementById('field_signature').value = response.razorpay_signature;
            document.getElementById('pay-form').submit();
        },
        theme: { color: '#0b2f71' }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
@endpush
