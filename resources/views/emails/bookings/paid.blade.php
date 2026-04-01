<x-mail::message>
# Payment confirmed

Hi {{ $booking->contact_name }},

We received payment for booking **{{ $booking->booking_code }}**.

**Amount paid:** Rs {{ number_format((float) $booking->total_amount, 2) }}  
**Travel date:** {{ $booking->travel_date->format('l, j F Y') }}

You can open your booking summary from the website while signed in, or contact support with your booking code.

<x-mail::subcopy>
Jet Fly Airways — thank you for choosing us.
</x-mail::subcopy>
</x-mail::message>
