<x-mail::message>
# Thanks, {{ $booking->contact_name }}

Your booking **{{ $booking->booking_code }}** is recorded for **{{ $itemTitle }}**.

**Travel date:** {{ $booking->travel_date->format('l, j F Y') }}  
**Travellers:** {{ $booking->travellers_count }}  
**Amount:** Rs {{ number_format((float) $booking->total_amount, 2) }}  
**Payment:** {{ $booking->payment_status === 'paid' ? 'Paid' : 'Pending' }}

@if($paymentCheckoutUrl)
<x-mail::button :url="$paymentCheckoutUrl">
Pay securely online
</x-mail::button>

_Link expires in 7 days. If you did not request this, ignore this email._
@else
You can sign in to your account to view bookings when payment is enabled on this site.
@endif

<x-mail::subcopy>
Jet Fly Airways — this is an automated message.
</x-mail::subcopy>
</x-mail::message>
