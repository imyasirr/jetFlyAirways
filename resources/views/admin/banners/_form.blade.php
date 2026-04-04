@php /** @var \App\Models\Banner|null $banner */ @endphp
<label>Title (optional)
    <input type="text" name="title" value="{{ old('title', $banner?->title ?? '') }}" maxlength="200">
</label>

<label class="admin-field-full">Short description (optional)
    <textarea name="description" rows="3" maxlength="2000" placeholder="Shown on the slide over the image">{{ old('description', $banner?->description ?? '') }}</textarea>
</label>

<div class="admin-field-full">
    @include('admin.partials.image-upload', [
        'label' => $banner ? 'Replace banner image' : 'Banner image',
        'name' => 'image_file',
        'currentPath' => $banner->image ?? null,
        'required' => ! $banner,
        'hint' => 'JPEG, PNG, WebP or GIF — max 10 MB. Wide images (~1600×600) work best.',
    ])
</div>

<label>Link URL (optional)
    <input type="text" name="link" value="{{ old('link', $banner?->link ?? '') }}" maxlength="500" placeholder="https://… or /flights — used by the CTA button">
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
