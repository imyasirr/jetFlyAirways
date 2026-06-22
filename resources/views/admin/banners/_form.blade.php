@php /** @var \App\Models\Banner|null $banner */ @endphp
@php
    $tagRows = old('tags', $banner?->tagList() ?? []);
    if (! is_array($tagRows) || $tagRows === []) {
        $tagRows = [''];
    }
@endphp
<label>Title (optional)
    <input type="text" name="title" value="{{ old('title', $banner?->title ?? '') }}" maxlength="200">
</label>

<label class="admin-field-full">Short description (optional)
    <textarea name="description" rows="3" maxlength="2000" placeholder="Shown on the slide over the image">{{ old('description', $banner?->description ?? '') }}</textarea>
</label>

<div class="admin-field-full admin-repeater-block">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:8px;">
        <div>
            <strong style="font-size:13px;color:#334155;">Promo tags</strong>
            <p style="margin:4px 0 0;font-size:12px;color:#64748b;">Yellow pills above the title. Add one or more short labels (e.g. ✈ Flights from ₹999).</p>
        </div>
        @include('admin.partials.toggle', [
            'name' => 'show_tags',
            'label' => 'Show tags',
            'hint' => 'Hide all tags on this slide.',
            'checked' => old('show_tags', ($banner?->show_tags ?? true) ? '1' : '0') === '1',
            'compact' => true,
        ])
    </div>
    <div id="banner-tags-list" class="admin-repeater-list">
        @foreach($tagRows as $index => $tag)
            <div class="admin-repeater-row admin-repeater-row--inline" data-repeater-row>
                <label class="admin-field-full" style="margin:0;">Tag
                    <input type="text" name="tags[{{ $index }}]" value="{{ $tag }}" maxlength="120" placeholder="e.g. ✈ Flights from ₹999">
                </label>
                <button type="button" class="admin-icon-btn admin-icon-btn--danger admin-repeater-remove" data-repeater-remove aria-label="Remove tag">
                    <span class="material-symbols-outlined" aria-hidden="true">delete</span>
                </button>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn ghost admin-repeater-add" data-banner-tag-add>
        <span class="material-symbols-outlined" aria-hidden="true">add</span> Add tag
    </button>
</div>

<div class="admin-field-full">
    @if($banner)
        @include('admin.partials.image-upload', [
            'label' => 'Replace banner image',
            'name' => 'image_file',
            'currentPath' => $banner->image ?? null,
            'required' => false,
            'hint' => 'JPEG, PNG, WebP or GIF — max 10 MB. Best: 1920×720 px wide (2:1 ratio). Same ratio for all slides. Keep text in the centre.',
        ])
    @else
        <div style="margin-bottom:14px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Banner images</label>
            <p style="font-size:12px;color:#64748b;margin:0 0 8px;">Select one or multiple JPEG, PNG, WebP or GIF files. Each image becomes one slide. <strong>Recommended: 1920×720 px</strong> wide landscape — not square posters.</p>
            <input type="file" name="image_files[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple required style="font-size:14px;">
        </div>
    @endif
</div>

<label>Link URL (optional)
    <input type="text" name="link" value="{{ old('link', $banner?->link ?? '') }}" maxlength="500" placeholder="https://... or /flights - used by the CTA button">
</label>

<div class="admin-field-full" style="display:grid;gap:12px;">
    <label>Button label (optional)
        <input type="text" name="button_text" value="{{ old('button_text', $banner?->button_text ?? '') }}" maxlength="120" placeholder="e.g. View offer, Book now (defaults to Explore now)">
    </label>
    @include('admin.partials.toggle', [
        'name' => 'show_button',
        'label' => 'Show button',
        'hint' => 'Hide the CTA button on this slide.',
        'checked' => old('show_button', ($banner?->show_button ?? true) ? '1' : '0') === '1',
    ])
</div>

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

@push('scripts')
<script>
(function () {
    var list = document.getElementById('banner-tags-list');
    var addBtn = document.querySelector('[data-banner-tag-add]');
    if (!list || !addBtn) return;

    function reindexTags() {
        list.querySelectorAll('[data-repeater-row]').forEach(function (row, index) {
            var input = row.querySelector('input[type="text"]');
            if (input) input.name = 'tags[' + index + ']';
        });
        var count = list.querySelectorAll('[data-repeater-row]').length;
        list.querySelectorAll('[data-repeater-remove]').forEach(function (btn) {
            btn.disabled = count <= 1;
        });
    }

    function buildRow(index) {
        var row = document.createElement('div');
        row.className = 'admin-repeater-row admin-repeater-row--inline';
        row.setAttribute('data-repeater-row', '');
        row.innerHTML =
            '<label class="admin-field-full" style="margin:0;">Tag' +
                '<input type="text" name="tags[' + index + ']" value="" maxlength="120" placeholder="e.g. ✈ Flights from ₹999">' +
            '</label>' +
            '<button type="button" class="admin-icon-btn admin-icon-btn--danger admin-repeater-remove" data-repeater-remove aria-label="Remove tag">' +
                '<span class="material-symbols-outlined" aria-hidden="true">delete</span>' +
            '</button>';
        return row;
    }

    addBtn.addEventListener('click', function () {
        var index = list.querySelectorAll('[data-repeater-row]').length;
        list.appendChild(buildRow(index));
        reindexTags();
    });

    list.addEventListener('click', function (event) {
        var btn = event.target.closest('[data-repeater-remove]');
        if (!btn || btn.disabled) return;
        var row = btn.closest('[data-repeater-row]');
        if (!row || list.querySelectorAll('[data-repeater-row]').length <= 1) return;
        row.remove();
        reindexTags();
    });

    reindexTags();
})();
</script>
@endpush
