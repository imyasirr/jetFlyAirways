<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { font-size: 18px; color: #0b2f71; margin: 0 0 8px; }
        .code { font-size: 22px; font-weight: bold; letter-spacing: 0.05em; color: #0b2f71; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        td { padding: 6px 0; border-bottom: 1px solid #e2e8f0; }
        td:first-child { color: #64748b; width: 38%; }
    </style>
</head>
<body>
    <h1>Jet Fly Airways — E-ticket</h1>
    <p class="code">{{ $booking->booking_code }}</p>
    <p style="margin:4px 0 16px;">Payment: <strong>{{ strtoupper($booking->payment_status) }}</strong></p>
    <table>
        <tr><td>Service</td><td>{{ $booking->module }}</td></tr>
        <tr><td>Item ID</td><td>#{{ $booking->module_item_id }}</td></tr>
        <tr><td>Travel date</td><td>{{ $booking->travel_date->format('d M Y') }}</td></tr>
        <tr><td>Travellers</td><td>{{ $booking->travellers_count }}</td></tr>
        <tr><td>Total</td><td>₹{{ number_format((float) $booking->total_amount, 2) }}</td></tr>
        @if($booking->contact_name)
            <tr><td>Passenger</td><td>{{ $booking->contact_name }}</td></tr>
        @endif
        @if($booking->contact_email)
            <tr><td>Email</td><td>{{ $booking->contact_email }}</td></tr>
        @endif
        @if($booking->razorpay_payment_id)
            <tr><td>Payment ref</td><td>{{ $booking->razorpay_payment_id }}</td></tr>
        @endif
    </table>
    <p style="margin-top:20px;font-size:10px;color:#94a3b8;">This document is issued electronically. Carry a valid ID for travel.</p>
</body>
</html>
