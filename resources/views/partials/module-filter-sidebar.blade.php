@php
    $preserveKeys = match ($slug) {
        'flights' => ['from', 'to', 'date', 'trip_type', 'return_date', 'travellers'],
        'hotels' => ['city', 'check_in', 'check_out', 'guests', 'q'],
        'packages' => ['destination', 'category', 'q'],
        'buses', 'trains' => ['pnr'],
        'cabs' => ['q'],
        default => [],
    };
@endphp

<form method="get" action="{{ route('module.index', $slug) }}" class="jfa-filter-sidebar__form" id="jfa-module-filters">
    @foreach($preserveKeys as $key)
        @if(request()->filled($key))
            <input type="hidden" name="{{ $key }}" value="{{ request($key) }}">
        @endif
    @endforeach

    @if($slug === 'flights')
        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Price range (₹)</span>
            <div class="jfa-filter-range">
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" step="100">
                <span>–</span>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" step="100">
            </div>
        </div>

        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Airline</span>
            <input type="text" name="airline" value="{{ request('airline') }}" placeholder="e.g. IndiGo, Air India">
        </div>

        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Cabin class</span>
            <div class="jfa-filter-options">
                @foreach(['' => 'Any', 'Economy' => 'Economy', 'Premium Economy' => 'Premium Economy', 'Business' => 'Business', 'First' => 'First'] as $val => $lbl)
                    <label class="jfa-filter-option">
                        <input type="radio" name="cabin_class" value="{{ $val }}" @checked(request('cabin_class', '') === $val)>
                        <span>{{ $lbl }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Stops</span>
            <div class="jfa-filter-options">
                @foreach(['' => 'Any', '0' => 'Non-stop', '1' => '1 stop', '2' => '2+ stops'] as $val => $lbl)
                    <label class="jfa-filter-option">
                        <input type="radio" name="stops" value="{{ $val }}" @checked((string) request('stops', '') === (string) $val)>
                        <span>{{ $lbl }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @elseif($slug === 'hotels')
        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Price per night (₹)</span>
            <div class="jfa-filter-range">
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" step="100">
                <span>–</span>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" step="100">
            </div>
        </div>

        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Star rating</span>
            <div class="jfa-filter-options">
                @foreach(['' => 'Any', '5' => '5 star', '4' => '4 star & up', '3' => '3 star & up', '2' => 'Budget (2★+)'] as $val => $lbl)
                    <label class="jfa-filter-option">
                        <input type="radio" name="min_stars" value="{{ $val }}" @checked((string) request('min_stars', '') === (string) $val)>
                        <span>{{ $lbl }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @elseif($slug === 'packages')
        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Destination</span>
            <input type="text" name="destination" value="{{ request('destination') }}" placeholder="Kerala, Dubai, Bali…">
        </div>
        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Category</span>
            <input type="text" name="category" value="{{ request('category') }}" placeholder="Family, Honeymoon…">
        </div>
        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Keyword</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Package name">
        </div>
    @elseif(in_array($slug, ['buses', 'trains'], true))
        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Route</span>
            <input type="text" name="from" value="{{ request('from') }}" placeholder="From city">
            <input type="text" name="to" value="{{ request('to') }}" placeholder="To city" style="margin-top:8px;">
            <input type="date" name="date" value="{{ request('date') }}" style="margin-top:8px;">
        </div>
    @elseif($slug === 'cabs')
        <div class="jfa-filter-group">
            <span class="jfa-filter-group__label">Search</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Service type, from, to…">
        </div>
    @endif

    <div class="jfa-filter-sidebar__actions">
        <button type="submit" class="btn" style="width:100%;justify-content:center;">Apply filters</button>
        <a class="btn secondary" href="{{ route('module.index', $slug) }}" style="width:100%;justify-content:center;">Reset</a>
    </div>
</form>
