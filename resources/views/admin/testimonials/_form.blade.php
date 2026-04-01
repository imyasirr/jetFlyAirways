@php $t = $testimonial ?? null; @endphp
<div style="display:grid;gap:12px;max-width:640px;">
    <label>Name <input type="text" name="name" value="{{ old('name', $t?->name) }}" required></label>
    <label>Designation <input type="text" name="designation" value="{{ old('designation', $t?->designation) }}"></label>
    <label>Review <textarea name="review" rows="5" required>{{ old('review', $t?->review) }}</textarea></label>
    <label>Rating (1–5) <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $t?->rating ?? 5) }}" required></label>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $t?->is_active ?? true))> Active (show on homepage)
    </label>
</div>
