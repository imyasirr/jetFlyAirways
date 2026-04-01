@csrf
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
    <div><label>Name</label><input name="name" required value="{{ old('name', $hotel->name ?? '') }}"></div>
    <div><label>City</label><input name="city" required value="{{ old('city', $hotel->city ?? '') }}"></div>
    <div><label>Location</label><input name="location" value="{{ old('location', $hotel->location ?? '') }}"></div>
    <div><label>Star Rating</label><input type="number" min="1" max="5" name="star_rating" required value="{{ old('star_rating', $hotel->star_rating ?? 3) }}"></div>
    <div><label>Price/Night</label><input type="number" step="0.01" min="0" name="price_per_night" required value="{{ old('price_per_night', $hotel->price_per_night ?? 0) }}"></div>
    <div><label>Amenities (comma separated)</label><input name="amenities" value="{{ old('amenities', isset($hotel) && is_array($hotel->amenities) ? implode(', ', $hotel->amenities) : '') }}"></div>
</div>
<div style="margin-top:10px;"><label>Description</label><textarea name="description" rows="4">{{ old('description', $hotel->description ?? '') }}</textarea></div>
<div style="margin-top:10px;"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $hotel->is_active ?? true) ? 'checked' : '' }}> Active</label></div>
<button class="btn" style="margin-top:12px;">Save Hotel</button>
