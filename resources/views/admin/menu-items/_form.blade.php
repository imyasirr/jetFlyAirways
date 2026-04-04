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
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Inactive links are hidden from the header or footer menu.',
        'checked' => old('is_active', ($item?->is_active ?? true) ? '1' : '0') === '1',
    ])
    @include('admin.partials.toggle', [
        'name' => 'open_new_tab',
        'label' => 'Open in new tab',
        'hint' => 'Uses target=_blank so visitors keep your site open.',
        'checked' => old('open_new_tab', ($item?->open_new_tab ?? false) ? '1' : '0') === '1',
        'withHidden' => false,
    ])
    @include('admin.partials.toggle', [
        'name' => 'requires_auth',
        'label' => 'Show only when logged in',
        'hint' => 'Link appears only for signed-in customers (e.g. My bookings).',
        'checked' => old('requires_auth', ($item?->requires_auth ?? false) ? '1' : '0') === '1',
        'withHidden' => false,
    ])
</div>
