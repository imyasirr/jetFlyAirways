@csrf
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
    <div><label>Service Type</label><input name="service_type" required value="{{ old('service_type', optional($service)->service_type) }}" placeholder="Airport / Outstation / Hourly"></div>
    <div><label>From</label><input name="from_location" required value="{{ old('from_location', optional($service)->from_location) }}"></div>
    <div><label>To</label><input name="to_location" value="{{ old('to_location', optional($service)->to_location) }}"></div>
    <div><label>Base Fare</label><input type="number" step="0.01" min="0" name="base_fare" required value="{{ old('base_fare', optional($service)->base_fare) }}"></div>
    <div><label>Per KM Rate</label><input type="number" step="0.01" min="0" name="per_km_rate" value="{{ old('per_km_rate', optional($service)->per_km_rate) }}"></div>
</div>
<div style="margin-top:10px;"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', optional($service)->is_active ?? true) ? 'checked' : '' }}> Active</label></div>
<button class="btn" style="margin-top:12px;">Save Cab Service</button>
