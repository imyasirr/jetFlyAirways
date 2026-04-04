@csrf
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
    <div><label>Train Name</label><input name="train_name" required value="{{ old('train_name', optional($route)->train_name) }}"></div>
    <div><label>Train Number</label><input name="train_number" required value="{{ old('train_number', optional($route)->train_number) }}"></div>
    <div><label>From City</label><input name="from_city" required value="{{ old('from_city', optional($route)->from_city) }}"></div>
    <div><label>To City</label><input name="to_city" required value="{{ old('to_city', optional($route)->to_city) }}"></div>
    <div><label>Departure</label><input type="datetime-local" name="departure_at" required value="{{ old('departure_at', optional($route)->departure_at?->format('Y-m-d\TH:i')) }}"></div>
    <div><label>Arrival</label><input type="datetime-local" name="arrival_at" required value="{{ old('arrival_at', optional($route)->arrival_at?->format('Y-m-d\TH:i')) }}"></div>
    <div><label>Price</label><input type="number" step="0.01" min="0" name="price" required value="{{ old('price', optional($route)->price) }}"></div>
    <div><label>Seats</label><input type="number" min="0" name="seats_available" required value="{{ old('seats_available', optional($route)->seats_available ?? 0) }}"></div>
</div>
<div style="margin-top:14px;">
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Inactive routes are omitted from public train search.',
        'checked' => old('is_active', (optional($route)->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>
<button class="btn" style="margin-top:12px;">Save Train Route</button>
