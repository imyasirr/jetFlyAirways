@php /** @var \App\Models\Banner|null $banner */ @endphp
<label>Title (optional)
    <input type="text" name="title" value="{{ old('title', $banner?->title ?? '') }}" maxlength="200">
</label>

<label class="admin-field-full">Short description (optional)
    <textarea name="description" rows="3" maxlength="2000" placeholder="Shown on the slide over the image">{{ old('description', $banner?->description ?? '') }}</textarea>
</label>

<div class="admin-field-full">
    @if($banner)
        @include('admin.partials.image-upload', [
            'label' => 'Replace banner image',
            'name' => 'image_file',
            'currentPath' => $banner->image ?? null,
            'required' => false,
            'hint' => 'JPEG, PNG, WebP or GIF - max 10 MB. Wide images around 1800x900 work best.',
        ])
    @else
        <div style="margin-bottom:14px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Banner images</label>
            <p style="font-size:12px;color:#64748b;margin:0 0 8px;">Select one or multiple JPEG, PNG, WebP or GIF files. Each image will become one carousel slide.</p>
            <input type="file" name="image_files[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple required style="font-size:14px;">
        </div>
    @endif
</div>

<label>Link URL (optional)
    <input type="text" name="link" value="{{ old('link', $banner?->link ?? '') }}" maxlength="500" placeholder="https://... or /flights - used by the CTA button">
</label>

<label>Button label (optional)
    <input type="text" name="button_text" value="{{ old('button_text', $banner?->button_text ?? '') }}" maxlength="120" placeholder="e.g. View offer, Book now (defaults to Explore if link is set)">
</label>

<label>Sort order
    <input type="number" name="sort_order" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}" min="0" max="99999">
</label>

<div class="admin-field-full">
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Inactive banners are hidden from the home page carousel.',
        'checked' => old('is_active', ($banner?->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>
