@csrf

@if($errors->any())
    <div class="flxf-errors">
        <span class="material-symbols-outlined" aria-hidden="true">error</span>
        <div>
            <strong>Please fix the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="flxf-section">
    <h3 class="flxf-section-title">
        <span class="material-symbols-outlined" aria-hidden="true">airlines</span>
        Airline details
    </h3>
    <div class="admin-form-grid">
        <div>
            <label>Airline</label>
            <input name="airline" required placeholder="e.g. Jet Fly Airways" value="{{ old('airline', $flight->airline ?? '') }}">
        </div>
        <div>
            <label>Flight number</label>
            <input name="flight_number" required placeholder="e.g. JF-203" value="{{ old('flight_number', $flight->flight_number ?? '') }}">
        </div>
    </div>
</div>

<div class="flxf-section">
    <h3 class="flxf-section-title">
        <span class="material-symbols-outlined" aria-hidden="true">route</span>
        Route &amp; schedule
    </h3>
    <div class="admin-form-grid">
        <div>
            <label>From city</label>
            <input name="from_city" required placeholder="e.g. Delhi" value="{{ old('from_city', $flight->from_city ?? '') }}">
        </div>
        <div>
            <label>To city</label>
            <input name="to_city" required placeholder="e.g. Mumbai" value="{{ old('to_city', $flight->to_city ?? '') }}">
        </div>
        <div>
            <label>Departure</label>
            <input type="datetime-local" name="departure_at" required value="{{ old('departure_at', isset($flight) ? $flight->departure_at?->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div>
            <label>Arrival</label>
            <input type="datetime-local" name="arrival_at" required value="{{ old('arrival_at', isset($flight) ? $flight->arrival_at?->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div>
            <label>Stops</label>
            <select name="stops" required>
                @foreach([0 => 'Non-stop', 1 => '1 stop', 2 => '2 stops', 3 => '3 stops', 4 => '4 stops', 5 => '5 stops'] as $stopCount => $label)
                    <option value="{{ $stopCount }}" {{ (string) old('stops', $flight->stops ?? 0) === (string) $stopCount ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Cabin class</label>
            @php
                $cabinCurrent = old('cabin_class', $flight->cabin_class ?? 'Economy');
                $cabinOptions = ['Economy', 'Premium Economy', 'Business', 'First'];
                if ($cabinCurrent && ! in_array($cabinCurrent, $cabinOptions, true)) {
                    array_unshift($cabinOptions, $cabinCurrent);
                }
            @endphp
            <select name="cabin_class" required>
                @foreach($cabinOptions as $cabin)
                    <option value="{{ $cabin }}" {{ $cabinCurrent === $cabin ? 'selected' : '' }}>{{ $cabin }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="flxf-section">
    <h3 class="flxf-section-title">
        <span class="material-symbols-outlined" aria-hidden="true">payments</span>
        Pricing &amp; seats
    </h3>
    <div class="admin-form-grid">
        <div>
            <label>Price (₹)</label>
            <input type="number" step="0.01" min="0" name="price" required placeholder="e.g. 4999" value="{{ old('price', $flight->price ?? '') }}">
        </div>
        <div>
            <label>Seats available</label>
            <input type="number" min="0" name="seats_available" required placeholder="e.g. 120" value="{{ old('seats_available', $flight->seats_available ?? 0) }}">
        </div>
    </div>
</div>

<div class="flxf-section flxf-section--last">
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Turn off to hide this flight from public search and listings.',
        'checked' => old('is_active', ($flight->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>

<div class="form-actions flxf-actions">
    <button class="btn flxf-save" type="submit">
        <span class="material-symbols-outlined" aria-hidden="true">save</span>
        {{ isset($flight) ? 'Save changes' : 'Create flight' }}
    </button>
    <a href="{{ route('admin.flights.index') }}" class="btn secondary">Cancel</a>
</div>
