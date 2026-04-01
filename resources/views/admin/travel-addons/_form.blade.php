@php /** @var \App\Models\TravelAddon|null $addon */ @endphp
<label>Category</label>
<select name="category" required>
    @foreach(\App\Models\TravelAddon::categories() as $cat)
        <option value="{{ $cat }}" {{ old('category', $addon->category ?? '') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
    @endforeach
</select>

<label>Name</label>
<input type="text" name="name" value="{{ old('name', $addon->name ?? '') }}" required maxlength="200">

<label>Summary (listing)</label>
<textarea name="summary" rows="2" maxlength="500">{{ old('summary', $addon->summary ?? '') }}</textarea>

<label>Description (detail page)</label>
<textarea name="description" rows="6" maxlength="10000">{{ old('description', $addon->description ?? '') }}</textarea>

<label>Price (Rs)</label>
<input type="number" name="price" value="{{ old('price', $addon->price ?? '') }}" required min="0" step="0.01">

<label>Sort order</label>
<input type="number" name="sort_order" value="{{ old('sort_order', $addon->sort_order ?? 0) }}" min="0" max="99999">

<label style="display:flex;align-items:center;gap:8px;font-weight:600;">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active', ($addon->is_active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
    Active
</label>
