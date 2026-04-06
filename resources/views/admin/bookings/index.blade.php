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

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Module</th>
                        <th>Travel</th>
                        <th>Guests</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td>{{ $b->booking_code }}</td>
                            <td>{{ $b->module }}</td>
                            <td>{{ $b->travel_date?->format('d M Y') }}</td>
                            <td>{{ $b->travellers_count }}</td>
                            <td>Rs {{ number_format($b->total_amount, 2) }}</td>
                            <td>{{ $b->payment_status }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner"><a class="btn secondary" href="{{ route('admin.bookings.show', $b) }}">View</a></div></td>
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

