@php $c = $card ?? null; @endphp
<div class="admin-form-grid" style="max-width:640px;">
    <label>Title
        <input type="text" name="title" value="{{ old('title', $c?->title) }}" required placeholder="Secure payments">
    </label>
    <label>Sort order
        <input type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $c?->sort_order ?? 10) }}">
    </label>
    <label class="admin-field-full">Description
        <textarea name="description" rows="3" required placeholder="Short benefit text shown on the homepage">{{ old('description', $c?->description) }}</textarea>
    </label>
    @include('admin.partials.icon-picker', [
        'name' => 'icon',
        'value' => old('icon', $c?->icon ?? 'verified'),
        'label' => 'Icon',
    ])
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Show this card in the homepage trust row.',
        'checked' => old('is_active', ($c?->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>
