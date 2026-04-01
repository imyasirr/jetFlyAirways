@extends('layouts.account')

@section('title', 'Offers & discounts')

@section('heading', 'Offers & discounts')

@section('content')
    <div class="acct-card">
        <p style="margin:0 0 16px;color:var(--acct-muted);">Apply these codes at checkout when payment integration is enabled. Availability is limited by validity dates and usage caps.</p>
        @forelse($coupons as $c)
            <div style="border:1px solid var(--acct-border);border-radius:12px;padding:16px 18px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                <div>
                    <strong style="font-size:1.1rem;letter-spacing:.06em;color:var(--acct-primary);">{{ $c->code }}</strong>
                    <p style="margin:6px 0 0;font-size:14px;color:var(--acct-muted);">
                        @if($c->discount_type === 'percent')
                            {{ rtrim(rtrim(number_format((float) $c->discount_value, 2), '0'), '.') }}% off
                        @else
                            ₹{{ number_format((float) $c->discount_value, 2) }} flat off
                        @endif
                        @if($c->valid_from || $c->valid_to)
                            · Valid
                            @if($c->valid_from) from {{ $c->valid_from->format('d M Y') }} @endif
                            @if($c->valid_to) until {{ $c->valid_to->format('d M Y') }} @endif
                        @endif
                    </p>
                </div>
                @if($c->isCurrentlyValid())
                    <span class="badge">Active</span>
                @else
                    <span class="badge" style="background:#fef2f2;color:#b91c1c;">Not active</span>
                @endif
            </div>
        @empty
            <p style="color:var(--acct-muted);">No coupons in the system yet. Ask your admin to add offers in the database.</p>
        @endforelse
    </div>
@endsection
