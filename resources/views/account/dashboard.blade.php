@extends('layouts.account')

@section('title', 'Overview')

@section('heading', 'Overview')

@section('content')
    <div class="acct-stats">
        <div class="acct-stat"><strong>{{ $stats['total_bookings'] }}</strong><span>Total bookings</span></div>
        <div class="acct-stat"><strong>{{ $stats['upcoming'] }}</strong><span>Upcoming trips</span></div>
        <div class="acct-stat"><strong>₹{{ number_format($stats['total_spent'], 0) }}</strong><span>Total spent</span></div>
        <div class="acct-stat"><strong>{{ $activeCoupons }}</strong><span>Live offers</span></div>
    </div>

    <div class="acct-card">
        <h2>Recent bookings</h2>
        @if($bookings->isEmpty())
            <p style="color:var(--acct-muted);margin:0;">No bookings yet. <a href="{{ route('home') }}" style="color:var(--acct-accent);font-weight:700;">Search &amp; book</a> while signed in to see them here.</p>
        @else
            <table class="acct-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Service</th>
                        <th>Travel date</th>
                        <th>Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                        <tr>
                            <td><strong>{{ $b->booking_code }}</strong></td>
                            <td>{{ $b->module }}</td>
                            <td>{{ $b->travel_date->format('d M Y') }}</td>
                            <td>₹{{ number_format((float) $b->total_amount, 2) }}</td>
                            <td><a href="{{ route('account.bookings.show', $b) }}" class="btn" style="padding:6px 12px;font-size:13px;">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="margin:16px 0 0;"><a href="{{ route('account.bookings.index') }}" style="color:var(--acct-accent);font-weight:700;">All bookings →</a></p>
        @endif
    </div>

    <div class="acct-card">
        <h2>Refer friends</h2>
        <p style="margin:0 0 10px;color:var(--acct-muted);font-size:14px;">Share your code; when someone registers with your link, we link their account to you.</p>
        @if($referralShareUrl)
            <p style="margin:0 0 8px;"><strong>Your code:</strong> <code style="background:#f0fdfa;padding:4px 8px;border-radius:6px;">{{ auth()->user()->referral_code }}</code></p>
            <p style="margin:0 0 12px;font-size:13px;word-break:break-all;"><strong>Link:</strong> <a href="{{ $referralShareUrl }}" style="color:var(--acct-accent);">{{ $referralShareUrl }}</a></p>
            <p style="margin:0;font-size:14px;color:var(--acct-muted);">Friends joined: <strong style="color:var(--acct-primary);">{{ $referralsCount }}</strong></p>
        @else
            <p style="margin:0;color:var(--acct-muted);">Referral code will appear after your profile is fully set up.</p>
        @endif
    </div>

    <div class="acct-card">
        <h2>Quick links</h2>
        <p style="margin:0 0 12px;color:var(--acct-muted);">Manage your trips, wallet offers, and profile.</p>
        <a href="{{ route('account.offers') }}" class="btn" style="margin-right:10px;">View offers</a>
        <a href="{{ route('account.profile.edit') }}" class="btn outline">Edit profile</a>
    </div>
@endsection
