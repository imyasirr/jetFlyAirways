@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="admin-dashboard">
        <div class="admin-dash-hero">
            <div>
                <h2 class="admin-dash-hero__title">Overview</h2>
                <p class="admin-dash-hero__sub">Live snapshot of bookings, website content, inventory, and leads.</p>
            </div>
            <div class="admin-dash-hero__actions">
                <a href="{{ route('home') }}" class="btn ghost" target="_blank" rel="noopener noreferrer">
                    <span class="material-symbols-outlined" aria-hidden="true">open_in_new</span>
                    View site
                </a>
                <a href="{{ route('admin.pages.create') }}" class="btn">
                    <span class="material-symbols-outlined" aria-hidden="true">add</span>
                    New CMS page
                </a>
            </div>
        </div>

        <div class="admin-dash-kpis">
            @foreach($headline as $kpi)
                @if($kpi['route'])
                    <a href="{{ route($kpi['route']) }}" class="admin-dash-kpi">
                @else
                    <div class="admin-dash-kpi">
                @endif
                    <span class="admin-dash-kpi__icon"><span class="material-symbols-outlined">{{ $kpi['icon'] }}</span></span>
                    <span class="admin-dash-kpi__label">{{ $kpi['label'] }}</span>
                    <strong class="admin-dash-kpi__value">{{ $kpi['value'] }}</strong>
                    <span class="admin-dash-kpi__hint">{{ $kpi['hint'] }}</span>
                @if($kpi['route'])
                    </a>
                @else
                    </div>
                @endif
            @endforeach
        </div>

        <div class="admin-dash-split">
            <section class="admin-dash-section admin-dash-section--cms">
                <div class="admin-dash-section__head">
                    <div>
                        <h3><span class="material-symbols-outlined" aria-hidden="true">language</span> Website &amp; CMS</h3>
                        <p>Pages, banners, marketing content, and homepage modules.</p>
                    </div>
                    <a href="{{ route('admin.pages.index') }}" class="admin-dash-section__link">Manage all</a>
                </div>

                <div class="admin-dash-links">
                    @foreach($cmsQuickLinks as $link)
                        <a href="{{ route($link['route']) }}" class="admin-dash-link" @if($link['route'] === 'home') target="_blank" rel="noopener noreferrer" @endif>
                            <span class="material-symbols-outlined" aria-hidden="true">{{ $link['icon'] }}</span>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="admin-dash-grid">
                    @foreach($cmsStats as $stat)
                        <a href="{{ route($stat['route']) }}" class="admin-dash-stat">
                            <span class="admin-dash-stat__icon"><span class="material-symbols-outlined">{{ $stat['icon'] }}</span></span>
                            <span class="admin-dash-stat__label">{{ $stat['label'] }}</span>
                            <strong class="admin-dash-stat__value">{{ number_format($stat['count']) }}</strong>
                            @if($stat['meta'])
                                <span class="admin-dash-stat__meta">{{ $stat['meta'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="admin-dash-note">
                    <span class="material-symbols-outlined" aria-hidden="true">{{ ($paymentGateway['configured'] ?? false) ? 'check_circle' : 'info' }}</span>
                    <span>
                        Payment gateway:
                        @if($paymentGateway['configured'] ?? false)
                            <strong>Razorpay ready</strong> via {{ ($paymentGateway['source'] ?? '') === 'admin' ? 'admin panel' : '.env' }}.
                        @else
                            Not configured — add keys in <a href="{{ route('admin.integrations.index') }}">API integrations</a>.
                        @endif
                    </span>
                </div>
            </section>

            <div class="admin-dash-stack">
                <section class="admin-dash-section">
                    <div class="admin-dash-section__head">
                        <div>
                            <h3><span class="material-symbols-outlined" aria-hidden="true">payments</span> Commerce</h3>
                            <p>Bookings, coupons, and promotional offers.</p>
                        </div>
                    </div>
                    <div class="admin-dash-grid admin-dash-grid--compact">
                        @foreach($commerceStats as $stat)
                            <a href="{{ route($stat['route']) }}" class="admin-dash-stat">
                                <span class="admin-dash-stat__icon"><span class="material-symbols-outlined">{{ $stat['icon'] }}</span></span>
                                <span class="admin-dash-stat__label">{{ $stat['label'] }}</span>
                                <strong class="admin-dash-stat__value">{{ number_format($stat['count']) }}</strong>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="admin-dash-section">
                    <div class="admin-dash-section__head">
                        <div>
                            <h3><span class="material-symbols-outlined" aria-hidden="true">inbox</span> Leads</h3>
                            <p>Contact forms, careers, and applications.</p>
                        </div>
                    </div>
                    <div class="admin-dash-grid admin-dash-grid--compact">
                        @foreach($leadStats as $stat)
                            <a href="{{ route($stat['route']) }}" class="admin-dash-stat">
                                <span class="admin-dash-stat__icon"><span class="material-symbols-outlined">{{ $stat['icon'] }}</span></span>
                                <span class="admin-dash-stat__label">{{ $stat['label'] }}</span>
                                <strong class="admin-dash-stat__value">{{ number_format($stat['count']) }}</strong>
                                @if($stat['meta'])
                                    <span class="admin-dash-stat__meta">{{ $stat['meta'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <section class="admin-dash-section">
            <div class="admin-dash-section__head">
                <div>
                    <h3><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span> Travel inventory</h3>
                    <p>Flights, hotels, packages, and ground transport listings.</p>
                </div>
            </div>
            <div class="admin-dash-grid">
                @foreach($inventoryStats as $stat)
                    <a href="{{ route($stat['route']) }}" class="admin-dash-stat">
                        <span class="admin-dash-stat__icon"><span class="material-symbols-outlined">{{ $stat['icon'] }}</span></span>
                        <span class="admin-dash-stat__label">{{ $stat['label'] }}</span>
                        <strong class="admin-dash-stat__value">{{ number_format($stat['count']) }}</strong>
                        @if($stat['meta'])
                            <span class="admin-dash-stat__meta">{{ $stat['meta'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>

        <section class="admin-dash-section admin-dash-section--table">
            <div class="admin-dash-section__head">
                <div>
                    <h3><span class="material-symbols-outlined" aria-hidden="true">receipt_long</span> Recent bookings</h3>
                    <p>Latest customer bookings across all modules.</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="admin-dash-section__link">All bookings</a>
            </div>

            @if($recentBookings->isEmpty())
                <div class="admin-dash-empty">
                    <span class="material-symbols-outlined" aria-hidden="true">receipt_long</span>
                    <p>No bookings yet.</p>
                </div>
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
                                <th>Payment</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $b)
                                <tr>
                                    <td><strong>{{ $b->booking_code }}</strong></td>
                                    <td>{{ ucfirst($b->module) }}</td>
                                    <td>{{ $b->travel_date?->format('d M Y') ?? '—' }}</td>
                                    <td>{{ $b->travellers_count }}</td>
                                    <td>₹{{ number_format((float) $b->total_amount, 2) }}</td>
                                    <td>
                                        <span class="admin-dash-badge admin-dash-badge--{{ $b->payment_status === 'paid' ? 'success' : 'pending' }}">
                                            {{ ucfirst($b->payment_status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="admin-table-actions">
                                        @include('admin.partials.table-actions', [
                                            'view' => route('admin.bookings.show', $b),
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
