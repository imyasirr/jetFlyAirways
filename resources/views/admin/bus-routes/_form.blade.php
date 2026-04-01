@csrf
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
    <div><label>Operator</label><input name="operator_name" required value="{{ old('operator_name', optional($route)->operator_name) }}"></div>
    <div><label>From City</label><input name="from_city" required value="{{ old('from_city', optional($route)->from_city) }}"></div>
    <div><label>To City</label><input name="to_city" required value="{{ old('to_city', optional($route)->to_city) }}"></div>
    <div><label>Departure</label><input type="datetime-local" name="departure_at" required value="{{ old('departure_at', optional($route)->departure_at?->format('Y-m-d\TH:i')) }}"></div>
    <div><label>Arrival</label><input type="datetime-local" name="arrival_at" required value="{{ old('arrival_at', optional($route)->arrival_at?->format('Y-m-d\TH:i')) }}"></div>
    <div><label>Price</label><input type="number" step="0.01" min="0" name="price" required value="{{ old('price', optional($route)->price) }}"></div>
    <div><label>Seats</label><input type="number" min="0" name="seats_available" required value="{{ old('seats_available', optional($route)->seats_available ?? 0) }}"></div>
</div>
<div style="margin-top:10px;"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', optional($route)->is_active ?? true) ? 'checked' : '' }}> Active</label></div>
<button class="btn" style="margin-top:12px;">Save Bus Route</button>
