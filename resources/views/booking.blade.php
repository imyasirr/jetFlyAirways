@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('title', __('Book').' — '.$module['title'])

@section('content')
    @php $unit = $unitPrice ?? (float) ($item['price'] ?? 0); @endphp
    <div class="booking-shell">
        <header class="booking-page-head">
            <nav class="site-breadcrumbs" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('module.index', $slug) }}">{{ $module['title'] ?? ucfirst($slug) }}</a></li>
                    <li><a href="{{ route('module.show', ['module' => $slug, 'item' => $item['slug']]) }}">{{ \Illuminate\Support\Str::limit($item['title'], 48) }}</a></li>
                    <li><span aria-current="page">{{ __('Book') }}</span></li>
                </ol>
            </nav>
        </header>

        <article class="card booking-card">
        <p class="booking-hero-kicker">{{ $module['title'] ?? ucfirst($slug) }}</p>
        <h1 class="booking-hero-title">{{ __('Complete your booking') }}</h1>
        <p class="booking-card__intro">
            <strong>{{ $item['title'] }}</strong> — ₹{{ number_format($unit, 2) }} {{ __('per unit') }}
            <span style="font-size:13px;color:var(--muted);">({{ __('before trip multiplier & coupon') }})</span>
        </p>
        <form method="post" action="{{ route('booking.submit', ['module' => $slug, 'item' => $item['slug']]) }}" id="booking-form" data-unit-price="{{ $unit }}" data-slug="{{ $slug }}">
            @csrf
            @auth
                @if(isset($savedTravellers) && $savedTravellers->isNotEmpty())
                    <div style="margin-bottom:10px;">
                        <label>Saved traveller</label>
                        <select id="saved_traveller_pick">
                            <option value="">Select saved traveller</option>
                            @foreach($savedTravellers as $traveller)
                                <option
                                    value="{{ $traveller->id }}"
                                    data-name="{{ $traveller->full_name }}"
                                    data-email="{{ $traveller->email }}"
                                    data-phone="{{ $traveller->phone }}"
                                >{{ $traveller->full_name }}{{ $traveller->email ? ' · '.$traveller->email : '' }}</option>
                            @endforeach
                        </select>
                        <p style="margin:6px 0 0;font-size:12px;color:#64748b;">Manage travellers from your account dashboard.</p>
                    </div>
                @endif
            @endauth

            @if($slug === 'flights')
                <div class="booking-flight-steps" style="margin-bottom:14px;padding:14px;border:1px solid var(--border);border-radius:12px;background:var(--card);">
                    <p style="margin:0 0 10px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);">Flight options</p>
                    <label>Trip type</label>
                    <select name="trip_type" id="trip_type" required>
                        <option value="one_way" @selected(old('trip_type', 'one_way') === 'one_way')>One way</option>
                        <option value="round_trip" @selected(old('trip_type') === 'round_trip')>Round trip</option>
                        <option value="multi_city" @selected(old('trip_type') === 'multi_city')>Multi city</option>
                    </select>
                    <div id="return_date_wrap" style="margin-top:10px;display:none;">
                        <label>Return date</label>
                        <input type="date" name="return_date" value="{{ old('return_date') }}">
                    </div>
                    <div id="multi_city_wrap" style="margin-top:10px;display:none;">
                        <label>Multi-city notes (segments / cities)</label>
                        <textarea name="multi_city_notes" rows="3" placeholder="e.g. DEL → BOM (12 Jun), BOM → GOA (15 Jun)">{{ old('multi_city_notes') }}</textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px;">
                        <div>
                            <label>Seat preference</label>
                            <select name="seat_preference">
                                <option value="">No preference</option>
                                <option value="window" @selected(old('seat_preference') === 'window')>Window</option>
                                <option value="aisle" @selected(old('seat_preference') === 'aisle')>Aisle</option>
                                <option value="middle" @selected(old('seat_preference') === 'middle')>Middle</option>
                            </select>
                        </div>
                        <div>
                            <label>Meal preference</label>
                            <select name="meal_preference">
                                <option value="">Standard</option>
                                <option value="veg" @selected(old('meal_preference') === 'veg')>Vegetarian</option>
                                <option value="non_veg" @selected(old('meal_preference') === 'non_veg')>Non-vegetarian</option>
                                <option value="jain" @selected(old('meal_preference') === 'jain')>Jain</option>
                            </select>
                        </div>
                    </div>
                    <p style="margin:10px 0 0;font-size:12px;color:var(--muted);">Fare estimate: round trip ×2, multi-city ×1.5 per traveller (inventory-based until live GDS).</p>
                </div>
            @endif

            <div class="booking-fare-summary" style="margin:14px 0;padding:14px;border-radius:12px;border:1px dashed var(--border);background:rgba(0,140,255,0.04);">
                <p style="margin:0 0 8px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);">Fare summary (estimate)</p>
                <p style="margin:0;font-size:14px;">{{ __('Unit') }}: ₹{{ number_format($unit, 2) }} × <span id="fare-tr-count">{{ old('travellers', 1) }}</span> {{ __('traveller(s)') }}
                    @if($slug === 'flights')
                        <span id="fare-mult-note"> · Trip ×<span id="fare-mult-val">1</span></span>
                    @endif
                </p>
                <p style="margin:8px 0 0;font-weight:700;" id="fare-subtotal-line">{{ __('Estimated subtotal') }}: ₹{{ number_format($unit * (int) old('travellers', 1), 2) }}</p>
                <p style="margin:6px 0 0;font-size:12px;color:var(--muted);">Final amount after valid coupon is applied on submit.</p>
            </div>

            <div style="margin-bottom:12px;">
                <label for="coupon_code">Coupon code (optional)</label>
                <input id="coupon_code" type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="SAVE10" maxlength="40" style="max-width:280px;">
                @error('coupon_code')
                    <p style="margin:6px 0 0;font-size:13px;color:#b91c1c;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div><label>Name</label><input name="name" required value="{{ old('name', auth()->user()?->name) }}"></div>
                <div><label>Email</label><input type="email" name="email" required value="{{ old('email', auth()->user()?->email) }}"></div>
                <div><label>Phone</label><input name="phone" required value="{{ old('phone', auth()->user()?->phone) }}"></div>
                <div><label>Travellers</label><input type="number" min="1" max="20" name="travellers" required value="{{ old('travellers', 1) }}"></div>
                <div><label>Travel Date</label><input type="date" name="travel_date" id="travel_date" required value="{{ old('travel_date') }}"></div>
            </div>
            @auth
                <label style="display:flex;align-items:center;gap:8px;margin-top:10px;font-size:14px;color:#475569;">
                    <input type="checkbox" name="save_traveller" value="1" style="width:auto;margin:0;">
                    Save this contact as traveller
                </label>
            @endauth
            <div style="margin-top:10px;">
                <label>Special Notes</label>
                <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>
            <div class="form-actions">
                <button class="btn checkout-pay-btn" type="submit">{{ __('Continue to payment') }}</button>
            </div>
        </form>
        </article>
    </div>
    <script>
        (function () {
            var form = document.getElementById('booking-form');
            if (!form) return;
            var unit = parseFloat(form.getAttribute('data-unit-price') || '0') || 0;
            var slug = form.getAttribute('data-slug') || '';
            var travInput = form.querySelector('input[name="travellers"]');
            var trip = document.getElementById('trip_type');
            var subLine = document.getElementById('fare-subtotal-line');
            var trLabel = document.getElementById('fare-tr-count');
            var multVal = document.getElementById('fare-mult-val');
            function mult() {
                if (slug !== 'flights' || !trip) return 1;
                if (trip.value === 'round_trip') return 2;
                if (trip.value === 'multi_city') return 1.5;
                return 1;
            }
            function refresh() {
                var n = parseInt(travInput && travInput.value ? travInput.value : '1', 10) || 1;
                if (trLabel) trLabel.textContent = String(n);
                if (multVal) multVal.textContent = String(mult());
                var sub = unit * n * mult();
                if (subLine) subLine.textContent = @json(__('Estimated subtotal')) + ': ₹' + sub.toFixed(2);
            }
            if (travInput) travInput.addEventListener('input', refresh);
            if (trip) trip.addEventListener('change', refresh);
            refresh();
        })();
    </script>
    @if($slug === 'flights')
        <script>
            (function () {
                var trip = document.getElementById('trip_type');
                var retWrap = document.getElementById('return_date_wrap');
                var multiWrap = document.getElementById('multi_city_wrap');
                if (!trip || !retWrap || !multiWrap) return;
                function sync() {
                    var v = trip.value;
                    retWrap.style.display = v === 'round_trip' ? 'block' : 'none';
                    multiWrap.style.display = v === 'multi_city' ? 'block' : 'none';
                }
                trip.addEventListener('change', sync);
                sync();
            })();
        </script>
    @endif
    @auth
        @if(isset($savedTravellers) && $savedTravellers->isNotEmpty())
            <script>
                (function () {
                    var select = document.getElementById('saved_traveller_pick');
                    if (!select) return;
                    var nameInput = document.querySelector('input[name="name"]');
                    var emailInput = document.querySelector('input[name="email"]');
                    var phoneInput = document.querySelector('input[name="phone"]');
                    select.addEventListener('change', function () {
                        var option = select.options[select.selectedIndex];
                        if (!option || !option.value) return;
                        if (nameInput && option.dataset.name) nameInput.value = option.dataset.name;
                        if (emailInput && option.dataset.email) emailInput.value = option.dataset.email;
                        if (phoneInput && option.dataset.phone) phoneInput.value = option.dataset.phone;
                    });
                })();
            </script>
        @endif
    @endauth
@endsection
