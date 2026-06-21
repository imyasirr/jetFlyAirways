@php $t = $testimonial ?? null; @endphp
<div class="admin-form-grid" style="max-width:640px;">
    <label>Name
        <input type="text" name="name" value="{{ old('name', $t?->name) }}" required>
    </label>
    <label>Designation
        <input type="text" name="designation" value="{{ old('designation', $t?->designation) }}" placeholder="e.g. Frequent flyer · Mumbai">
    </label>
    <div class="admin-field-full">
        @include('admin.partials.image-upload', [
            'label' => 'Photo or company logo',
            'name' => 'photo_file',
            'currentPath' => $t?->photo,
            'required' => false,
            'hint' => 'Optional. Customer photo or brand logo shown on the website. Square PNG/WebP works best. Max 4 MB.',
        ])
    </div>
    @if($t?->photo)
        <label class="admin-field-full admin-checkbox-row">
            <input type="checkbox" name="clear_photo" value="1" @checked(old('clear_photo'))>
            <span>Remove photo / logo</span>
        </label>
    @endif
    <label class="admin-field-full">Review
        <textarea name="review" rows="5" required>{{ old('review', $t?->review) }}</textarea>
    </label>
    <label>Rating (1–5)
        <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $t?->rating ?? 5) }}" required>
    </label>
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Show this testimonial in homepage and marketing sections.',
        'checked' => old('is_active', ($t?->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>
