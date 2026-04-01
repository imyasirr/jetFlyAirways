@php $p = $popup ?? null; @endphp
<div style="display:grid;gap:12px;max-width:560px;">
    <label>Title <input type="text" name="title" value="{{ old('title', $p?->title) }}"></label>
    <label>Message <textarea name="message" rows="5">{{ old('message', $p?->message) }}</textarea></label>
    @include('admin.partials.image-upload', [
        'label' => 'Popup image',
        'name' => 'image_file',
        'currentPath' => $p?->image,
        'required' => false,
        'hint' => 'Optional. JPEG, PNG, WebP or GIF — max 10 MB.',
    ])
    <label>Button text <input type="text" name="button_text" value="{{ old('button_text', $p?->button_text) }}"></label>
    <label>Redirect link <input type="text" name="redirect_link" value="{{ old('redirect_link', $p?->redirect_link) }}"></label>
    <label>Start date <input type="date" name="start_date" value="{{ old('start_date', $p?->start_date?->format('Y-m-d')) }}"></label>
    <label>End date <input type="date" name="end_date" value="{{ old('end_date', $p?->end_date?->format('Y-m-d')) }}"></label>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p?->is_active ?? false))> Active
    </label>
</div>
