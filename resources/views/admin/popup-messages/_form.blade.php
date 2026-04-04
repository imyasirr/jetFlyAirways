@php $p = $popup ?? null; @endphp
<div class="admin-form-grid">
    <label>Title
        <input type="text" name="title" value="{{ old('title', $p?->title) }}">
    </label>
    <label>Button text
        <input type="text" name="button_text" value="{{ old('button_text', $p?->button_text) }}">
    </label>
    <label>Redirect link
        <input type="text" name="redirect_link" value="{{ old('redirect_link', $p?->redirect_link) }}">
    </label>
    <label>Start date
        <input type="date" name="start_date" value="{{ old('start_date', $p?->start_date?->format('Y-m-d')) }}">
    </label>
    <label>End date
        <input type="date" name="end_date" value="{{ old('end_date', $p?->end_date?->format('Y-m-d')) }}">
    </label>
    <label class="admin-field-full">Message
        <textarea name="message" rows="5">{{ old('message', $p?->message) }}</textarea>
    </label>
    <div class="admin-field-full">
        @include('admin.partials.image-upload', [
            'label' => 'Popup image',
            'name' => 'image_file',
            'currentPath' => $p?->image,
            'required' => false,
            'hint' => 'Optional. JPEG, PNG, WebP or GIF — max 10 MB.',
        ])
    </div>
    <div class="admin-field-full">
        @include('admin.partials.toggle', [
            'name' => 'is_active',
            'label' => 'Active',
            'hint' => 'Only active popups within the date range can appear for visitors.',
            'checked' => old('is_active', ($p?->is_active ?? false) ? '1' : '0') === '1',
        ])
    </div>
</div>
