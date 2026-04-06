@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:700px;">
        <h1 class="section-title">Book {{ $module['title'] }}</h1>
        <p><strong>{{ $item['title'] }}</strong> — Rs {{ number_format($item['price'] ?? 0, 2) }} per unit</p>
        <form method="post" action="{{ route('booking.submit', ['module' => $slug, 'id' => $id]) }}" id="booking-form">
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
                    <p style="margin:10px 0 0;font-size:12px;color:var(--muted);">Fare estimate: round trip ×2, multi-city ×1.5 per traveller (stub until live GDS).</p>
                </div>
            @endif

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
                <button class="btn">Continue to Payment</button>
            </div>
        </form>
    </div>
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
