@extends('layouts.app')

@section('body_class', 'page-ota-flow')

@section('title', __('Book').' — '.$module['title'])

@section('content')
    @php $unit = $unitPrice ?? (float) ($item['price'] ?? 0); @endphp

    <div class="jfa-booking-steps">
        <span class="jfa-booking-step is-done">Search</span>
        <span class="jfa-booking-step is-done">Details</span>
        <span class="jfa-booking-step is-active">Book</span>
        <span class="jfa-booking-step">Pay</span>
        <span class="jfa-booking-step">Confirm</span>
    </div>

    <nav class="jfa-breadcrumb" aria-label="Breadcrumb" style="margin-bottom:20px;">
        <a href="{{ route('home') }}">{{ __('Home') }}</a>
        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
        <a href="{{ route('module.index', $slug) }}">{{ $module['title'] ?? ucfirst($slug) }}</a>
        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
        <span aria-current="page">{{ __('Book') }}</span>
    </nav>

    <article class="jfa-card">
        <p style="margin:0 0 4px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--jfa-muted);">{{ $module['title'] ?? ucfirst($slug) }}</p>
        <h1 style="margin:0 0 8px;font-size:1.5rem;">{{ __('Complete your booking') }}</h1>
        <p style="margin:0 0 24px;color:var(--jfa-muted);">
            <strong style="color:var(--jfa-on-surface);">{{ $item['title'] }}</strong> — ₹{{ number_format($unit, 2) }} {{ __('per unit') }}
        </p>

        <form method="post" action="{{ route('booking.submit', ['module' => $slug, 'item' => $item['slug']]) }}" id="booking-form" data-unit-price="{{ $unit }}" data-slug="{{ $slug }}">
            @csrf
            @auth
                @if(isset($savedTravellers) && $savedTravellers->isNotEmpty())
                    <div style="margin-bottom:16px;">
                        <label class="jfa-label">Saved traveller</label>
                        <select id="saved_traveller_pick">
                            <option value="">Select saved traveller</option>
                            @foreach($savedTravellers as $traveller)
                                <option value="{{ $traveller->id }}" data-name="{{ $traveller->full_name }}" data-email="{{ $traveller->email }}" data-phone="{{ $traveller->phone }}">{{ $traveller->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            @endauth

            @if($slug === 'flights')
                <div class="jfa-card" style="padding:16px;margin-bottom:16px;background:var(--jfa-surface-low);box-shadow:none;">
                    <p style="margin:0 0 12px;font-size:12px;font-weight:800;text-transform:uppercase;color:var(--jfa-muted);">Flight options</p>
                    <label class="jfa-label">Trip type</label>
                    <select name="trip_type" id="trip_type" required>
                        <option value="one_way" @selected(old('trip_type', 'one_way') === 'one_way')>One way</option>
                        <option value="round_trip" @selected(old('trip_type') === 'round_trip')>Round trip</option>
                        <option value="multi_city" @selected(old('trip_type') === 'multi_city')>Multi city</option>
                    </select>
                    <div id="return_date_wrap" style="margin-top:12px;display:none;">
                        <label class="jfa-label">Return date</label>
                        <input type="date" name="return_date" value="{{ old('return_date') }}">
                    </div>
                </div>
            @endif

            <div class="jfa-fare-box">
                <p style="margin:0 0 8px;font-size:12px;font-weight:800;text-transform:uppercase;color:var(--jfa-muted);">Fare summary (estimate)</p>
                <p style="margin:0;font-size:14px;">₹{{ number_format($unit, 2) }} × <span id="fare-tr-count">{{ old('travellers', 1) }}</span> {{ __('traveller(s)') }}</p>
                <p style="margin:8px 0 0;font-weight:700;" id="fare-subtotal-line">{{ __('Estimated subtotal') }}: ₹{{ number_format($unit * (int) old('travellers', 1), 2) }}</p>
            </div>

            <div style="margin:16px 0;">
                <label class="jfa-label" for="coupon_code">Coupon code (optional)</label>
                <input id="coupon_code" type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="SAVE10" maxlength="40" style="max-width:280px;">
                @error('coupon_code')<p style="margin:6px 0 0;font-size:13px;color:var(--jfa-alert);">{{ $message }}</p>@enderror
            </div>

            <div class="jfa-search__grid">
                <div><label class="jfa-label">Name</label><input name="name" required value="{{ old('name', auth()->user()?->name) }}"></div>
                <div><label class="jfa-label">Email</label><input type="email" name="email" required value="{{ old('email', auth()->user()?->email) }}"></div>
                <div><label class="jfa-label">Phone</label><input name="phone" required value="{{ old('phone', auth()->user()?->phone) }}"></div>
                <div><label class="jfa-label">Travellers</label><input type="number" min="1" max="20" name="travellers" required value="{{ old('travellers', 1) }}"></div>
                <div><label class="jfa-label">Travel Date</label><input type="date" name="travel_date" required value="{{ old('travel_date') }}"></div>
            </div>

            <div style="margin-top:12px;"><label class="jfa-label">Special Notes</label><textarea name="notes" rows="4">{{ old('notes') }}</textarea></div>

            <div class="form-actions" style="margin-top:20px;">
                <button class="btn" type="submit">{{ __('Continue to payment') }}</button>
            </div>
        </form>
    </article>

    <script>
    (function () {
        var form = document.getElementById('booking-form');
        if (!form) return;
        var unit = parseFloat(form.getAttribute('data-unit-price') || '0') || 0;
        var travInput = form.querySelector('input[name="travellers"]');
        var subLine = document.getElementById('fare-subtotal-line');
        var trLabel = document.getElementById('fare-tr-count');
        function refresh() {
            var n = parseInt(travInput && travInput.value ? travInput.value : '1', 10) || 1;
            if (trLabel) trLabel.textContent = String(n);
            if (subLine) subLine.textContent = @json(__('Estimated subtotal')) + ': ₹' + (unit * n).toFixed(2);
        }
        if (travInput) travInput.addEventListener('input', refresh);
    })();
    </script>
@endsection
