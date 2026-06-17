@extends('layouts.account')

@section('title', 'Overview')
@section('heading', 'Overview')

@section('content')
    <div class="jfa-stat-grid">
        <div class="jfa-stat"><strong>{{ $stats['total_bookings'] }}</strong><span>Total bookings</span></div>
        <div class="jfa-stat"><strong>{{ $stats['upcoming'] }}</strong><span>Upcoming trips</span></div>
        <div class="jfa-stat"><strong>₹{{ number_format($stats['total_spent'], 0) }}</strong><span>Total spent</span></div>
        <div class="jfa-stat"><strong>{{ $activeCoupons }}</strong><span>Live offers</span></div>
    </div>

    <div class="jfa-card" style="margin-bottom:24px;">
        <h2 style="margin:0 0 16px;font-size:1.1rem;">Recent bookings</h2>
        @if($bookings->isEmpty())
            <p style="color:var(--jfa-muted);margin:0;">No bookings yet. <a href="{{ route('home') }}" style="color:var(--jfa-booking-blue);font-weight:700;">Search &amp; book</a> while signed in.</p>
        @else
            <div class="jfa-table-wrap">
                <table class="jfa-table">
                    <thead>
                        <tr><th>Code</th><th>Service</th><th>Travel date</th><th>Amount</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $b)
                            <tr>
                                <td><strong>{{ $b->booking_code }}</strong></td>
                                <td>{{ $b->module }}</td>
                                <td>{{ $b->travel_date->format('d M Y') }}</td>
                                <td>₹{{ number_format((float) $b->total_amount, 2) }}</td>
                                <td><a href="{{ route('account.bookings.show', $b) }}" class="btn" style="padding:6px 14px;font-size:13px;">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p style="margin:16px 0 0;"><a href="{{ route('account.bookings.index') }}" class="jfa-link-arrow">All bookings <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span></a></p>
        @endif
    </div>

    <div class="jfa-card" style="margin-bottom:24px;">
        <h2 style="margin:0 0 12px;font-size:1.1rem;">Refer friends</h2>
        <p style="margin:0 0 12px;color:var(--jfa-muted);font-size:14px;">Share your code when friends register with your link.</p>
        @if($referralShareUrl)
            <p style="margin:0 0 8px;"><strong>Your code:</strong> <code style="background:var(--jfa-secondary-container);padding:4px 10px;border-radius:8px;font-weight:700;">{{ auth()->user()->referral_code }}</code></p>
            <p style="margin:0 0 12px;font-size:13px;word-break:break-all;"><a href="{{ $referralShareUrl }}" style="color:var(--jfa-booking-blue);">{{ $referralShareUrl }}</a></p>
            <p style="margin:0;font-size:14px;color:var(--jfa-muted);">Friends joined: <strong style="color:var(--jfa-on-surface);">{{ $referralsCount }}</strong></p>
        @else
            <p style="margin:0;color:var(--jfa-muted);">Referral code will appear after your profile is set up.</p>
        @endif
    </div>

    <div class="form-actions">
        <a href="{{ route('account.offers') }}" class="btn">View offers</a>
        <a href="{{ route('account.profile.edit') }}" class="btn secondary">Edit profile</a>
    </div>
@endsection
