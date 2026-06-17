@php
    $tripType = request('trip_type', 'one_way');
    $activeModule = $activeModule ?? 'flights';
    $compact = !empty($compact);
@endphp
<div class="jfa-search {{ $compact ? 'jfa-search--compact' : '' }}" id="jfa-search-panel">
    <div class="jfa-search__tabs" role="tablist" aria-label="Booking type">
        @foreach([
            ['id' => 'flights', 'label' => 'Flights', 'icon' => 'flight'],
            ['id' => 'hotels', 'label' => 'Hotels', 'icon' => 'hotel'],
            ['id' => 'packages', 'label' => 'Holidays', 'icon' => 'beach_access'],
            ['id' => 'trains', 'label' => 'Trains', 'icon' => 'train'],
            ['id' => 'buses', 'label' => 'Buses', 'icon' => 'directions_bus'],
            ['id' => 'cabs', 'label' => 'Cabs', 'icon' => 'local_taxi'],
            ['id' => 'visa', 'label' => 'Visa', 'icon' => 'travel_explore'],
            ['id' => 'insurance', 'label' => 'Insurance', 'icon' => 'shield'],
        ] as $tab)
            <button type="button" class="jfa-search__tab {{ $activeModule === $tab['id'] ? 'is-active' : '' }}" data-jfa-tab="{{ $tab['id'] }}" role="tab" aria-selected="{{ $activeModule === $tab['id'] ? 'true' : 'false' }}">
                <span class="material-symbols-outlined" style="font-size:18px;">{{ $tab['icon'] }}</span>
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <div class="jfa-search__panel {{ $activeModule === 'flights' ? 'is-active' : '' }}" data-jfa-panel="flights" role="tabpanel" @if($activeModule !== 'flights') hidden @endif>
        <form method="get" action="{{ route('module.index', 'flights') }}">
            <div class="jfa-search__pills" role="radiogroup" aria-label="Trip type">
                @foreach(['one_way' => 'One way', 'round_trip' => 'Round trip', 'multi_city' => 'Multi city'] as $val => $lbl)
                    <label class="jfa-search__pill {{ $tripType === $val ? 'is-active' : '' }}">
                        <input type="radio" name="trip_type" value="{{ $val }}" @checked($tripType === $val)>
                        {{ $lbl }}
                    </label>
                @endforeach
            </div>
            <div class="jfa-search__grid jfa-search__grid--4">
                <div><label class="jfa-label" for="hf-from">From</label><input id="hf-from" name="from" type="text" placeholder="City or airport" value="{{ request('from') }}"></div>
                <div><label class="jfa-label" for="hf-to">To</label><input id="hf-to" name="to" type="text" placeholder="City or airport" value="{{ request('to') }}"></div>
                <div><label class="jfa-label" for="hf-date">Departure</label><input id="hf-date" name="date" type="date" value="{{ request('date') }}"></div>
                <div id="hf-return-wrap" style="{{ $tripType !== 'round_trip' ? 'opacity:.55;' : '' }}">
                    <label class="jfa-label" for="hf-ret">Return</label>
                    <input id="hf-ret" name="return_date" type="date" value="{{ request('return_date') }}" @disabled($tripType !== 'round_trip')>
                </div>
                <div><label class="jfa-label" for="hf-trav">Travellers</label><input id="hf-trav" name="travellers" type="number" min="1" max="9" value="{{ request('travellers', 1) }}"></div>
                <div><label class="jfa-label" for="hf-class">Class</label>
                    <select id="hf-class" name="cabin_class">
                        <option value="">Any</option>
                        @foreach(['Economy', 'Premium Economy', 'Business', 'First'] as $c)
                            <option value="{{ $c }}" @selected(request('cabin_class') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="jfa-search__foot">
                <div class="jfa-search__quicklinks">
                    @foreach(['buses' => 'Buses', 'trains' => 'Trains', 'cabs' => 'Cabs', 'visa' => 'Visa', 'insurance' => 'Insurance'] as $slug => $lbl)
                        <button type="button" data-jfa-tab-trigger="{{ $slug }}">{{ $lbl }}</button>
                    @endforeach
                </div>
                <button type="submit" class="jfa-search__submit"><span class="material-symbols-outlined">search</span> Search</button>
            </div>
        </form>
    </div>

    <div class="jfa-search__panel {{ $activeModule === 'hotels' ? 'is-active' : '' }}" data-jfa-panel="hotels" role="tabpanel" @if($activeModule !== 'hotels') hidden @endif>
        <form method="get" action="{{ route('module.index', 'hotels') }}">
            <div class="jfa-search__grid jfa-search__grid--4">
                <div style="grid-column:span 2;"><label class="jfa-label" for="hh-city">City or Hotel</label><input id="hh-city" name="city" type="text" placeholder="Mumbai, Delhi, Goa…" value="{{ request('city') }}"></div>
                <div><label class="jfa-label" for="hh-in">Check-in</label><input id="hh-in" name="check_in" type="date" value="{{ request('check_in') }}"></div>
                <div><label class="jfa-label" for="hh-out">Check-out</label><input id="hh-out" name="check_out" type="date" value="{{ request('check_out') }}"></div>
                <div><label class="jfa-label" for="hh-guests">Guests</label><input id="hh-guests" name="guests" type="number" min="1" max="20" value="{{ request('guests', 2) }}"></div>
            </div>
            <div class="jfa-search__foot"><span></span><button type="submit" class="jfa-search__submit"><span class="material-symbols-outlined">search</span> Search</button></div>
        </form>
    </div>

    <div class="jfa-search__panel {{ $activeModule === 'packages' ? 'is-active' : '' }}" data-jfa-panel="packages" role="tabpanel" @if($activeModule !== 'packages') hidden @endif>
        <form method="get" action="{{ route('module.index', 'packages') }}">
            <div class="jfa-search__grid">
                <div><label class="jfa-label" for="hp-dest">Destination</label><input id="hp-dest" name="destination" type="text" placeholder="Kerala, Dubai, Bali" value="{{ request('destination') }}"></div>
                <div><label class="jfa-label" for="hp-cat">Category</label><input id="hp-cat" name="category" type="text" placeholder="Family, Honeymoon" value="{{ request('category') }}"></div>
                <div><label class="jfa-label" for="hp-q">Keyword</label><input id="hp-q" name="q" type="text" placeholder="Package name" value="{{ request('q') }}"></div>
            </div>
            <div class="jfa-search__foot"><span></span><button type="submit" class="jfa-search__submit"><span class="material-symbols-outlined">search</span> Search</button></div>
        </form>
    </div>

    @foreach(['trains', 'buses'] as $mod)
        <div class="jfa-search__panel {{ $activeModule === $mod ? 'is-active' : '' }}" data-jfa-panel="{{ $mod }}" role="tabpanel" @if($activeModule !== $mod) hidden @endif>
            <form method="get" action="{{ route('module.index', $mod) }}">
                <div class="jfa-search__grid">
                    <div><label class="jfa-label">From</label><input name="from" type="text" placeholder="Origin city" value="{{ request('from') }}"></div>
                    <div><label class="jfa-label">To</label><input name="to" type="text" placeholder="Destination city" value="{{ request('to') }}"></div>
                    <div><label class="jfa-label">Travel Date</label><input name="date" type="date" value="{{ request('date') }}"></div>
                </div>
                <div class="jfa-search__foot"><span></span><button type="submit" class="jfa-search__submit"><span class="material-symbols-outlined">search</span> Search</button></div>
            </form>
        </div>
    @endforeach

    <div class="jfa-search__panel {{ $activeModule === 'cabs' ? 'is-active' : '' }}" data-jfa-panel="cabs" role="tabpanel" @if($activeModule !== 'cabs') hidden @endif>
        <form method="get" action="{{ route('module.index', 'cabs') }}">
            <div class="jfa-search__grid">
                <div><label class="jfa-label">Pickup</label><input name="q" type="text" placeholder="Airport, hotel, city…" value="{{ request('q') }}"></div>
                <div><label class="jfa-label">Drop</label><input type="text" placeholder="Destination"></div>
            </div>
            <div class="jfa-search__foot"><span></span><button type="submit" class="jfa-search__submit"><span class="material-symbols-outlined">search</span> Search</button></div>
        </form>
    </div>

    @foreach(['visa', 'insurance'] as $mod)
        <div class="jfa-search__panel {{ $activeModule === $mod ? 'is-active' : '' }}" data-jfa-panel="{{ $mod }}" role="tabpanel" @if($activeModule !== $mod) hidden @endif>
            <div style="text-align:center;padding:24px 0;color:var(--jfa-muted);">
                <span class="material-symbols-outlined" style="font-size:40px;color:var(--jfa-primary-container);display:block;margin-bottom:8px;">{{ $mod === 'visa' ? 'travel_explore' : 'shield' }}</span>
                Browse our full {{ $mod === 'visa' ? 'Visa Services' : 'Travel Insurance' }} catalog.
            </div>
            <div class="jfa-search__foot" style="justify-content:center;">
                <a href="{{ route('module.index', $mod) }}" class="jfa-search__submit" style="text-decoration:none;display:inline-flex;">View catalog</a>
            </div>
        </div>
    @endforeach
</div>

<script>
(function () {
    var root = document.getElementById('jfa-search-panel');
    if (!root) return;

    function activateTab(id) {
        root.querySelectorAll('.jfa-search__tab').forEach(function (t) {
            var on = t.getAttribute('data-jfa-tab') === id;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        root.querySelectorAll('.jfa-search__panel').forEach(function (p) {
            var on = p.getAttribute('data-jfa-panel') === id;
            p.classList.toggle('is-active', on);
            p.hidden = !on;
        });
    }

    root.querySelectorAll('[data-jfa-tab]').forEach(function (btn) {
        btn.addEventListener('click', function () { activateTab(btn.getAttribute('data-jfa-tab')); });
    });
    root.querySelectorAll('[data-jfa-tab-trigger]').forEach(function (btn) {
        btn.addEventListener('click', function () { activateTab(btn.getAttribute('data-jfa-tab-trigger')); });
    });

    root.querySelectorAll('input[name="trip_type"]').forEach(function (r) {
        r.addEventListener('change', function () {
            var wrap = document.getElementById('hf-return-wrap');
            var ret = document.getElementById('hf-ret');
            if (!wrap || !ret) return;
            var round = r.value === 'round_trip' && r.checked;
            wrap.style.opacity = round ? '1' : '.55';
            ret.disabled = !round;
            root.querySelectorAll('.jfa-search__pill').forEach(function (pill) {
                pill.classList.toggle('is-active', pill.querySelector('input') && pill.querySelector('input').checked);
            });
        });
    });
})();
</script>
