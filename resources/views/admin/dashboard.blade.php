@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <p style="margin:0 0 18px;color:#64748b;max-width:72ch;line-height:1.5;">Use the cards below to jump to a module. Numbers reflect live rows in your database.</p>

    <div class="admin-card" style="margin-bottom:20px;">
        <h2 style="margin:0 0 12px;font-size:1rem;">Website &amp; customers</h2>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <a href="{{ route('admin.menu-items.index') }}" class="btn secondary">Header &amp; footer menu</a>
            <a href="{{ route('admin.site-settings.edit') }}" class="btn secondary">Site header &amp; footer</a>
            <a href="{{ route('admin.pages.index') }}" class="btn secondary">CMS pages</a>
            <a href="{{ route('admin.coupons.index') }}" class="btn secondary">Coupons</a>
            <a href="{{ route('admin.users.index') }}" class="btn secondary">Users</a>
        </div>
    </div>

    <div style="display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));margin-bottom:28px;">
        @foreach($stats as $row)
            @if($row['route'])
                <a href="{{ route($row['route']) }}" class="admin-card" style="display:block;transition:transform .12s,box-shadow .12s;text-decoration:none;color:inherit;">
                    <div style="font-size:1.75rem;line-height:1;margin-bottom:8px;">{{ $row['icon'] }}</div>
                    <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">{{ $row['label'] }}</div>
                    <div style="font-size:1.75rem;font-weight:800;color:#0b2f71;margin-top:6px;">{{ number_format($row['count']) }}</div>
                    <div style="font-size:13px;color:#0ea5e9;margin-top:8px;font-weight:600;">Open →</div>
                </a>
            @else
                <div class="admin-card" style="opacity:.92;">
                    <div style="font-size:1.75rem;line-height:1;margin-bottom:8px;">{{ $row['icon'] }}</div>
                    <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">{{ $row['label'] }}</div>
                    <div style="font-size:1.75rem;font-weight:800;color:#0b2f71;margin-top:6px;">{{ number_format($row['count']) }}</div>
                    <div style="font-size:13px;color:#94a3b8;margin-top:8px;">CMS panel coming soon</div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="admin-card">
        <h2>Recent bookings</h2>
        @if($recentBookings->isEmpty())
            <p style="margin:0;color:#64748b;">No bookings yet.</p>
        @else
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Module</th>
                            <th>Travel date</th>
                            <th>Guests</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $b)
                            <tr>
                                <td><strong>{{ $b->booking_code }}</strong></td>
                                <td>{{ $b->module }}</td>
                                <td>{{ $b->travel_date }}</td>
                                <td>{{ $b->travellers_count }}</td>
                                <td>₹{{ number_format((float) $b->total_amount, 2) }}</td>
                                <td>{{ $b->status }}</td>
                                <td><a href="{{ route('admin.bookings.show', $b->id) }}" style="color:#0ea5e9;font-weight:600;">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

