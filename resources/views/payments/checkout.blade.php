@extends('layouts.app')

@section('title', 'Pay — '.$booking->booking_code)

@section('content')
    <div class="card" style="max-width:520px;">
        <h1 class="section-title">Complete payment</h1>
        <p style="color:#64748b;font-size:14px;">Booking <strong>{{ $booking->booking_code }}</strong> · ₹{{ number_format((float) $booking->total_amount, 2) }}</p>
        <form id="pay-form" method="post" action="{{ route('payments.verify') }}">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <input type="hidden" name="razorpay_order_id" id="field_order_id" value="">
            <input type="hidden" name="razorpay_payment_id" id="field_payment_id" value="">
            <input type="hidden" name="razorpay_signature" id="field_signature" value="">
        </form>
        <button type="button" id="rzp-launch" class="btn" style="margin-top:16px;">Pay securely with Razorpay</button>
        <p style="font-size:13px;color:#64748b;margin-top:14px;">UPI, cards, netbanking — you will return to a confirmation page after payment.</p>
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
