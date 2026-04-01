@csrf
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
    <div><label>Category</label><input name="category" required value="{{ old('category', optional($package)->category) }}" placeholder="e.g. Honeymoon, Domestic"></div>
    <div><label>Package Name</label><input name="name" required value="{{ old('name', optional($package)->name) }}"></div>
    <div><label>Destination</label><input name="destination" required value="{{ old('destination', optional($package)->destination) }}"></div>
    <div><label>Duration (days)</label><input type="number" min="1" name="duration_days" required value="{{ old('duration_days', optional($package)->duration_days ?? 3) }}"></div>
    <div><label>Price</label><input type="number" step="0.01" min="0" name="price" required value="{{ old('price', optional($package)->price) }}"></div>
    <div><label>Offer Price</label><input type="number" step="0.01" min="0" name="offer_price" value="{{ old('offer_price', optional($package)->offer_price) }}"></div>
</div>
<div style="margin-top:10px;"><label>Itinerary</label><textarea name="itinerary" rows="4">{{ old('itinerary', optional($package)->itinerary) }}</textarea></div>
<div style="margin-top:10px;"><label>Details</label><textarea name="details" rows="4">{{ old('details', optional($package)->details) }}</textarea></div>
<div style="margin-top:10px;"><label>Inclusions</label><textarea name="inclusions" rows="3">{{ old('inclusions', optional($package)->inclusions) }}</textarea></div>
<div style="margin-top:10px;"><label>Exclusions</label><textarea name="exclusions" rows="3">{{ old('exclusions', optional($package)->exclusions) }}</textarea></div>
<div style="margin-top:10px;"><label><input type="checkbox" name="is_published" value="1" {{ old('is_published', optional($package)->is_published ?? true) ? 'checked' : '' }}> Published</label></div>
<button class="btn" style="margin-top:12px;">Save Package</button>
