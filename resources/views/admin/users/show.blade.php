@extends('layouts.admin')

@section('content')
    <div class="card" style="margin-bottom:16px;">
        <h1 class="section-title">User #{{ $user->id }}</h1>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Phone:</strong> {{ $user->phone ?? '—' }}</p>
        <p><strong>Referral code:</strong> {{ $user->referral_code ?? '—' }}</p>
        <p><strong>Referred by:</strong> @if($user->referrer) {{ $user->referrer->email }} @else — @endif</p>
        <p><strong>Referrals (signups):</strong> {{ $user->referred_users_count }}</p>
        <p><strong>Admin:</strong> {{ $user->is_admin ? 'Yes' : 'No' }}</p>
        <p><strong>Registered:</strong> {{ $user->created_at?->format('d M Y H:i') }}</p>
    </div>
    <div class="card">
        <h2 class="section-title" style="font-size:1.1rem;">Bookings (latest 50)</h2>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Module</th>
                        <th>Travel date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->bookings as $b)
                        <tr>
                            <td><a href="{{ route('admin.bookings.show', $b) }}">{{ $b->booking_code }}</a></td>
                            <td>{{ $b->module }}</td>
                            <td>{{ $b->travel_date->format('Y-m-d') }}</td>
                            <td>{{ $b->total_amount }}</td>
                            <td>{{ $b->status }}</td>
                            <td>{{ $b->payment_status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No bookings for this user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p style="margin-top:12px;"><a href="{{ route('admin.users.index') }}" class="btn secondary">← Back to users</a></p>
    </div>
@endsection

