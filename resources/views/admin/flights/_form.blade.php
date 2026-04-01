@csrf
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
    <div><label>Airline</label><input name="airline" required value="{{ old('airline', $flight->airline ?? '') }}"></div>
    <div><label>Flight Number</label><input name="flight_number" required value="{{ old('flight_number', $flight->flight_number ?? '') }}"></div>
    <div><label>From City</label><input name="from_city" required value="{{ old('from_city', $flight->from_city ?? '') }}"></div>
    <div><label>To City</label><input name="to_city" required value="{{ old('to_city', $flight->to_city ?? '') }}"></div>
    <div><label>Departure</label><input type="datetime-local" name="departure_at" required value="{{ old('departure_at', isset($flight) ? $flight->departure_at?->format('Y-m-d\TH:i') : '') }}"></div>
    <div><label>Arrival</label><input type="datetime-local" name="arrival_at" required value="{{ old('arrival_at', isset($flight) ? $flight->arrival_at?->format('Y-m-d\TH:i') : '') }}"></div>
    <div><label>Price</label><input type="number" step="0.01" min="0" name="price" required value="{{ old('price', $flight->price ?? '') }}"></div>
    <div><label>Stops</label><input type="number" min="0" max="5" name="stops" required value="{{ old('stops', $flight->stops ?? 0) }}"></div>
    <div><label>Cabin Class</label><input name="cabin_class" required value="{{ old('cabin_class', $flight->cabin_class ?? 'Economy') }}"></div>
    <div><label>Seats Available</label><input type="number" min="0" name="seats_available" required value="{{ old('seats_available', $flight->seats_available ?? 0) }}"></div>
</div>
<div style="margin-top:10px;">
    <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $flight->is_active ?? true) ? 'checked' : '' }}> Active</label>
</div>
<button class="btn" style="margin-top:12px;">Save Flight</button>
