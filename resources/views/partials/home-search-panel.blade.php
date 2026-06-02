<div class="home-ota-shell" id="search">
    <div class="home-ota-card">
        <div class="home-ota-tabs" role="tablist" aria-label="Booking type">
            <button type="button" class="home-ota-tab is-active" role="tab" aria-selected="true" aria-controls="home-ota-panel-flights" id="home-ota-tab-flights" data-home-tab="flights">
                <span class="home-ota-tab-icon" aria-hidden="true">FL</span> Flights
            </button>
            <button type="button" class="home-ota-tab" role="tab" aria-selected="false" aria-controls="home-ota-panel-hotels" id="home-ota-tab-hotels" data-home-tab="hotels">
                <span class="home-ota-tab-icon" aria-hidden="true">HT</span> Hotels
            </button>
            <button type="button" class="home-ota-tab" role="tab" aria-selected="false" aria-controls="home-ota-panel-packages" id="home-ota-tab-packages" data-home-tab="packages">
                <span class="home-ota-tab-icon" aria-hidden="true">HL</span> Holidays
            </button>
        </div>
        <p class="home-ota-quicklinks">
            <span class="home-ota-quicklabel">Also book:</span>
            <a href="{{ route('module.index', 'buses') }}">Buses</a>
            <a href="{{ route('module.index', 'trains') }}">Trains</a>
            <a href="{{ route('module.index', 'cabs') }}">Cabs</a>
            <a href="{{ route('module.index', 'visa') }}">Visa</a>
            <a href="{{ route('module.index', 'insurance') }}">Insurance</a>
        </p>

        <div id="home-ota-panel-flights" class="home-ota-panel is-active" role="tabpanel" aria-labelledby="home-ota-tab-flights">
            <form method="get" action="{{ route('module.index', 'flights') }}" class="home-ota-form">
                <div class="home-ota-fields home-ota-fields--flights">
                    <div class="home-ota-field">
                        <label for="hf-trip">Trip</label>
                        <select id="hf-trip" name="trip_type" data-home-flight-trip>
                            <option value="one_way" @selected(request('trip_type', 'one_way') === 'one_way')>One way</option>
                            <option value="round_trip" @selected(request('trip_type') === 'round_trip')>Round trip</option>
                            <option value="multi_city" @selected(request('trip_type') === 'multi_city')>Multi city</option>
                        </select>
                    </div>
                    <div class="home-ota-field">
                        <label for="hf-from">From</label>
                        <input id="hf-from" name="from" type="text" placeholder="City or airport" value="{{ request('from') }}" autocomplete="off">
                    </div>
                    <div class="home-ota-field">
                        <label for="hf-to">To</label>
                        <input id="hf-to" name="to" type="text" placeholder="City or airport" value="{{ request('to') }}" autocomplete="off">
                    </div>
                    <div class="home-ota-field home-ota-field--date">
                        <label for="hf-date">Departure</label>
                        <input id="hf-date" name="date" type="date" value="{{ request('date') }}">
                    </div>
                    <div class="home-ota-field home-ota-field--date" id="hf-return-wrap" @if(request('trip_type') !== 'round_trip') hidden @endif>
                        <label for="hf-ret">Return</label>
                        <input id="hf-ret" name="return_date" type="date" value="{{ request('return_date') }}">
                    </div>
                    <div class="home-ota-field">
                        <label for="hf-trav">Travellers</label>
                        <input id="hf-trav" name="travellers" type="number" min="1" max="9" value="{{ request('travellers', 1) }}">
                    </div>
                    <div class="home-ota-field">
                        <label for="hf-class">Class</label>
                        <select id="hf-class" name="cabin_class">
                            <option value="">Any</option>
                            <option value="Economy" @selected(request('cabin_class') === 'Economy')>Economy</option>
                            <option value="Premium Economy" @selected(request('cabin_class') === 'Premium Economy')>Premium Economy</option>
                            <option value="Business" @selected(request('cabin_class') === 'Business')>Business</option>
                            <option value="First" @selected(request('cabin_class') === 'First')>First</option>
                        </select>
                    </div>
                    <div class="home-ota-field home-ota-field--submit">
                        <span class="home-ota-field-spacer" aria-hidden="true"></span>
                        <button type="submit" class="home-ota-search-btn">Search flights</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="home-ota-panel-hotels" class="home-ota-panel" role="tabpanel" aria-labelledby="home-ota-tab-hotels" hidden>
            <form method="get" action="{{ route('module.index', 'hotels') }}" class="home-ota-form">
                <div class="home-ota-fields home-ota-fields--hotels">
                    <div class="home-ota-field home-ota-field--grow">
                        <label for="hh-city">City, property name</label>
                        <input id="hh-city" name="city" type="text" placeholder="Goa, Mumbai, Delhi" value="{{ request('city') }}">
                    </div>
                    <div class="home-ota-field">
                        <label for="hh-q">Hotel name (optional)</label>
                        <input id="hh-q" name="q" type="text" placeholder="Search by name" value="{{ request('q') }}">
                    </div>
                    <div class="home-ota-field home-ota-field--date">
                        <label for="hh-in">Check-in</label>
                        <input id="hh-in" name="check_in" type="date" value="{{ request('check_in') }}">
                    </div>
                    <div class="home-ota-field home-ota-field--date">
                        <label for="hh-out">Check-out</label>
                        <input id="hh-out" name="check_out" type="date" value="{{ request('check_out') }}">
                    </div>
                    <div class="home-ota-field">
                        <label for="hh-guests">Guests</label>
                        <input id="hh-guests" name="guests" type="number" min="1" max="20" value="{{ request('guests', 2) }}">
                    </div>
                    <div class="home-ota-field home-ota-field--submit">
                        <span class="home-ota-field-spacer" aria-hidden="true"></span>
                        <button type="submit" class="home-ota-search-btn home-ota-search-btn--hotels">Search hotels</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="home-ota-panel-packages" class="home-ota-panel" role="tabpanel" aria-labelledby="home-ota-tab-packages" hidden>
            <form method="get" action="{{ route('module.index', 'packages') }}" class="home-ota-form">
                <div class="home-ota-fields home-ota-fields--packages">
                    <div class="home-ota-field">
                        <label for="hp-dest">Destination</label>
                        <input id="hp-dest" name="destination" type="text" placeholder="Kerala, Dubai, Bali" value="{{ request('destination') }}">
                    </div>
                    <div class="home-ota-field">
                        <label for="hp-cat">Category</label>
                        <input id="hp-cat" name="category" type="text" placeholder="Family, Honeymoon" value="{{ request('category') }}">
                    </div>
                    <div class="home-ota-field">
                        <label for="hp-q">Keyword</label>
                        <input id="hp-q" name="q" type="text" placeholder="Package name" value="{{ request('q') }}">
                    </div>
                    <div class="home-ota-field home-ota-field--submit">
                        <span class="home-ota-field-spacer" aria-hidden="true"></span>
                        <button type="submit" class="home-ota-search-btn home-ota-search-btn--packages">Search packages</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    var tabs = document.querySelectorAll('.home-ota-tab');
    var panels = document.querySelectorAll('.home-ota-panel');
    if (!tabs.length || !panels.length) return;
    function activate(name) {
        tabs.forEach(function (t) {
            var on = t.getAttribute('data-home-tab') === name;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panels.forEach(function (p) {
            var on = p.id === 'home-ota-panel-' + name;
            p.classList.toggle('is-active', on);
            if (on) { p.removeAttribute('hidden'); }
            else { p.setAttribute('hidden', ''); }
        });
    }
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activate(tab.getAttribute('data-home-tab'));
        });
    });

    var trip = document.querySelector('[data-home-flight-trip]');
    var retWrap = document.getElementById('hf-return-wrap');
    if (trip && retWrap) {
        function syncTrip() {
            retWrap.hidden = trip.value !== 'round_trip';
        }
        trip.addEventListener('change', syncTrip);
        syncTrip();
    }
})();
</script>
