@php $d = $destination ?? null; @endphp
<div class="admin-form-grid" style="max-width:900px;">
    <label>Name
        <input type="text" name="name" value="{{ old('name', $d?->name) }}" required maxlength="120" placeholder="e.g. Goa, India">
    </label>
    <label>Tag line
        <input type="text" name="tag_line" value="{{ old('tag_line', $d?->tag_line) }}" maxlength="120" placeholder="e.g. Beach break">
    </label>
    <label>Best season
        <input type="text" name="best_season" value="{{ old('best_season', $d?->best_season) }}" maxlength="120" placeholder="e.g. Nov – Feb">
    </label>
    <label class="admin-field-full">Package search keyword
        <input type="text" name="package_destination" value="{{ old('package_destination', $d?->package_destination) }}" maxlength="120" placeholder="e.g. Goa (used for package filter link)">
    </label>

    <div class="cms-editor-wrap admin-field-full">
        <label for="dest_description">Overview description</label>
        <textarea id="dest_description" name="description" rows="10">{{ old('description', $d?->description) }}</textarea>
        <p class="cms-editor-hint">Short intro shown at the top of the detail page — highlights, vibe, and why travellers visit.</p>
    </div>

    <div class="cms-editor-wrap admin-field-full">
        <label for="dest_details">Travel details</label>
        <textarea id="dest_details" name="details" rows="14">{{ old('details', $d?->details) }}</textarea>
        <p class="cms-editor-hint">Longer section below the gallery — itineraries, tips, places to see, packing notes, etc.</p>
    </div>

    <div class="admin-field-full">
        @include('admin.partials.image-upload', [
            'label' => $d ? 'Replace banner image' : 'Banner image',
            'name' => 'banner_file',
            'currentPath' => $d?->banner,
            'required' => ! $d,
            'hint' => 'Main hero image for cards and detail page. Recommended 1200×700 px landscape. Max 10 MB.',
        ])
    </div>
    @if($d?->banner)
        <label class="admin-field-full admin-checkbox-row">
            <input type="checkbox" name="clear_banner" value="1" @checked(old('clear_banner'))>
            <span>Remove banner image</span>
        </label>
    @endif

    <div class="admin-field-full" style="padding:14px 0;border-top:1px solid var(--admin-border);">
        <strong style="display:block;margin-bottom:6px;">Photo gallery</strong>
        <p style="margin:0 0 10px;font-size:12px;color:var(--admin-muted);">Upload multiple images for the destination detail gallery. JPEG, PNG, WebP or GIF — max 10 MB each.</p>
        <input type="file" name="gallery_files[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
    </div>

    @if($d && $d->gallery->isNotEmpty())
        <div class="admin-field-full">
            <strong style="display:block;margin-bottom:8px;font-size:13px;">Existing gallery</strong>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">
                @foreach($d->gallery as $image)
                    <label style="display:block;border:1px solid var(--admin-border);border-radius:10px;overflow:hidden;background:#fff;">
                        <img src="{{ $image->imageUrl() }}" alt="" style="width:100%;height:96px;object-fit:cover;display:block;">
                        <span style="display:flex;align-items:center;gap:8px;padding:8px;font-size:12px;">
                            <input type="checkbox" name="gallery_delete_ids[]" value="{{ $image->id }}">
                            Delete
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    <label>Sort order
        <input type="number" name="sort_order" value="{{ old('sort_order', $d?->sort_order ?? 0) }}" min="0" max="99999">
    </label>
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Show on homepage and in the app.',
        'checked' => old('is_active', ($d?->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>
@include('admin.partials.tinymce-multi', [
    'fields' => [
        ['id' => 'dest_description', 'height' => 320],
        ['id' => 'dest_details', 'height' => 420],
    ],
])
