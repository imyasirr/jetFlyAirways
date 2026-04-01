@php
    $item = $menuItem ?? null;
@endphp
<div style="display:grid;gap:14px;max-width:520px;">
    <div>
        <label>Location</label>
        <select name="location" required>
            <option value="header" @selected(old('location', $item?->location) === 'header')>Header</option>
            <option value="footer" @selected(old('location', $item?->location) === 'footer')>Footer</option>
        </select>
    </div>
    <div>
        <label>Parent (optional — for submenu / mega column)</label>
        <select name="parent_id">
            <option value="">— None —</option>
            @foreach($parents as $p)
                @if(!$item || $p->id !== $item->id)
                    <option value="{{ $p->id }}" @selected(old('parent_id', $item?->parent_id) == $p->id)>{{ $p->label }} ({{ $p->location }})</option>
                @endif
            @endforeach
        </select>
    </div>
    <div>
        <label>Label</label>
        <input type="text" name="label" value="{{ old('label', $item?->label) }}" required maxlength="120">
    </div>
    <div>
        <label>URL path or full URL</label>
        <input type="text" name="href" value="{{ old('href', $item?->href) }}" placeholder="/flights or https://…">
        <span style="font-size:12px;color:#64748b;">Empty for mega-menu parent only.</span>
    </div>
    <div>
        <label>Sort order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $item?->sort_order ?? 0) }}" min="0" max="65535" required>
    </div>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item?->is_active ?? true))> Active
    </label>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="open_new_tab" value="1" @checked(old('open_new_tab', $item?->open_new_tab ?? false))> Open in new tab
    </label>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="requires_auth" value="1" @checked(old('requires_auth', $item?->requires_auth ?? false))> Show only when logged in
    </label>
</div>
