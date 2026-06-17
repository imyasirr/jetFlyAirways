@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('title', 'Pay — '.$booking->booking_code)

@section('content')
    <div class="jfa-booking-steps" style="margin-bottom:24px;">
        <span class="jfa-booking-step is-done">Search</span>
        <span class="jfa-booking-step is-done">Details</span>
        <span class="jfa-booking-step is-done">Book</span>
        <span class="jfa-booking-step is-active">Pay</span>
        <span class="jfa-booking-step">Confirm</span>
    </div>

    <article class="jfa-card page-card page-card--narrow" style="margin:0 auto;">
        <p style="margin:0 0 4px;font-size:12px;font-weight:700;text-transform:uppercase;color:var(--jfa-muted);">{{ __('Secure checkout') }}</p>
        <h1 style="margin:0 0 8px;">{{ __('Complete payment') }}</h1>
        <p style="margin:0 0 20px;color:var(--jfa-muted);">{{ __('Booking') }} <strong>{{ $booking->booking_code }}</strong></p>

        <div class="jfa-checkout-amount">
            <p style="margin:0 0 4px;font-size:13px;color:var(--jfa-muted);">{{ __('Total payable') }}</p>
            <p class="jfa-checkout-amount__value">₹{{ number_format((float) $booking->total_amount, 2) }}</p>
            <p style="margin:8px 0 0;font-size:13px;color:var(--jfa-muted);">{{ __('INR') }} · {{ __('Razorpay') }}</p>
        </div>

        @if((float) ($booking->discount_amount ?? 0) > 0)
            <p style="font-size:14px;color:var(--jfa-success);margin:0 0 16px;">
                {{ __('Coupon applied:') }} −₹{{ number_format((float) $booking->discount_amount, 2) }}
            </p>
        @endif

        <p style="text-align:center;font-size:13px;color:var(--jfa-muted);margin:0 0 20px;">
            {{ __('256-bit encryption') }} · {{ __('UPI · Cards · Netbanking') }}
        </p>

        <form id="pay-form" method="post" action="{{ route('payments.verify') }}">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <input type="hidden" name="razorpay_order_id" id="field_order_id" value="">
            <input type="hidden" name="razorpay_payment_id" id="field_payment_id" value="">
            <input type="hidden" name="razorpay_signature" id="field_signature" value="">
        </form>
        <button type="button" id="rzp-launch" class="btn" style="width:100%;">{{ __('Pay securely with Razorpay') }}</button>
        <p style="text-align:center;font-size:12px;color:var(--jfa-muted);margin:16px 0 0;">{{ __('You will return to a confirmation page after successful payment.') }}</p>
    </article>
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
        prefill: { name: @json($prefillName), email: @json($prefillEmail), contact: @json($prefillPhone) },
        handler: function (response) {
            document.getElementById('field_order_id').value = response.razorpay_order_id;
            document.getElementById('field_payment_id').value = response.razorpay_payment_id;
            document.getElementById('field_signature').value = response.razorpay_signature;
            document.getElementById('pay-form').submit();
        },
        theme: { color: '#003B95' }
    };
    new Razorpay(options).open();
});
</script>
@endpush
