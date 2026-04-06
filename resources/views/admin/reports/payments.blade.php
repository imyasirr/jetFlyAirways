@extends('layouts.admin')

@section('title', 'Payment reports')

@section('content')
    <div class="card">
        <h1 class="section-title">Payment reports</h1>
        <form method="get" action="{{ route('admin.reports.payments') }}" class="admin-form-grid" style="max-width:620px;">
            <label>From <input type="date" name="from" value="{{ request('from') }}"></label>
            <label>To <input type="date" name="to" value="{{ request('to') }}"></label>
            <div class="admin-field-full form-actions">
                <button type="submit" class="btn">Apply</button>
                <a href="{{ route('admin.reports.payments') }}" class="btn ghost">Reset</a>
            </div>
        </form>

        <div class="row" style="margin-top:14px;">
            <div class="admin-card"><strong>Paid</strong><div>₹{{ number_format((float) $totals['paid'], 2) }}</div></div>
            <div class="admin-card"><strong>Pending</strong><div>₹{{ number_format((float) $totals['pending'], 2) }}</div></div>
            <div class="admin-card"><strong>Refund initiated</strong><div>₹{{ number_format((float) $totals['refund_initiated'], 2) }}</div></div>
        </div>
    </div>

    <div class="card" style="margin-top:16px;">
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Module</th>
                        <th>Amount</th>
                        <th>Payment status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td><a href="{{ route('admin.bookings.show', $row) }}">{{ $row->booking_code }}</a></td>
                            <td>{{ $row->module }}</td>
                            <td>₹{{ number_format((float) $row->total_amount, 2) }}</td>
                            <td>{{ $row->payment_status }}</td>
                            <td>{{ $row->created_at?->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No payment data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $rows->links() }}</div>
    </div>
@endsection

