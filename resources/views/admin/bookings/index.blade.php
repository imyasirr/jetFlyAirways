@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;flex-wrap:wrap;">
            <h1 class="section-title" style="margin:0;">Bookings</h1>
            <a class="btn secondary" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>

        <form method="get" action="{{ route('admin.bookings.index') }}" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;margin-bottom:16px;align-items:end;">
            <div>
                <label style="font-size:12px;opacity:.85;">Module</label>
                <select name="module">
                    <option value="">All</option>
                    @foreach(['flights','hotels','packages','buses','trains','cabs'] as $m)
                        <option value="{{ $m }}" @selected(request('module') === $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:12px;opacity:.85;">Booking code</label>
                <input name="code" value="{{ request('code') }}" placeholder="JFA-...">
            </div>
            <div>
                <label style="font-size:12px;opacity:.85;">Payment</label>
                <select name="payment_status">
                    <option value="">All</option>
                    <option value="pending" @selected(request('payment_status') === 'pending')>Pending</option>
                    <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
                </select>
            </div>
            <div>
                <button class="btn" type="submit">Filter</button>
            </div>
        </form>

        <div style="overflow:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Code</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Module</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Travel</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Guests</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Amount</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Payment</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $b->booking_code }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $b->module }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $b->travel_date?->format('d M Y') }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $b->travellers_count }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">Rs {{ number_format($b->total_amount, 2) }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $b->payment_status }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;"><a class="btn secondary" href="{{ route('admin.bookings.show', $b) }}">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="padding:12px;">No bookings yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $bookings->links() }}</div>
    </div>
@endsection
