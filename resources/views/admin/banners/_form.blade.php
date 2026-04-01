@php /** @var \App\Models\Banner|null $banner */ @endphp
<label>Title (optional)</label>
<input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" maxlength="200">

@include('admin.partials.image-upload', [
    'label' => $banner ? 'Replace banner image' : 'Banner image',
    'name' => 'image_file',
    'currentPath' => $banner->image ?? null,
    'required' => ! $banner,
    'hint' => 'JPEG, PNG, WebP or GIF — max 10 MB. Wide images (~1600×600) work best.',
])

<label>Link when clicked (optional)</label>
<input type="text" name="link" value="{{ old('link', $banner->link ?? '') }}" maxlength="500" placeholder="https://… or /flights">

<label>Sort order</label>
<input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0" max="99999">

<label style="display:flex;align-items:center;gap:8px;font-weight:600;">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active', ($banner->is_active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
    Active
</label>
