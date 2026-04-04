@php $c = $coupon ?? null; @endphp
<div class="admin-form-grid">
    <label>Code
        <input type="text" name="code" value="{{ old('code', $c?->code) }}" required maxlength="40">
    </label>
    <label>Discount type
        <select name="discount_type" required>
            <option value="flat" @selected(old('discount_type', $c?->discount_type) === 'flat')>Flat (₹)</option>
            <option value="percent" @selected(old('discount_type', $c?->discount_type) === 'percent')>Percent (%)</option>
        </select>
    </label>
    <label>Value
        <input type="text" name="discount_value" value="{{ old('discount_value', $c?->discount_value) }}" required>
    </label>
    <label>Max usage (optional)
        <input type="number" name="max_usage" value="{{ old('max_usage', $c?->max_usage) }}" min="0">
    </label>
    <label>Valid from
        <input type="date" name="valid_from" value="{{ old('valid_from', $c?->valid_from?->format('Y-m-d')) }}">
    </label>
    <label>Valid to
        <input type="date" name="valid_to" value="{{ old('valid_to', $c?->valid_to?->format('Y-m-d')) }}">
    </label>
    <label>Used count
        <input type="number" name="used_count" value="{{ old('used_count', $c?->used_count ?? 0) }}" min="0">
    </label>
    <div class="admin-field-full">
        @include('admin.partials.toggle', [
            'name' => 'is_active',
            'label' => 'Active',
            'hint' => 'Guests cannot apply inactive coupon codes at checkout.',
            'checked' => old('is_active', ($c?->is_active ?? true) ? '1' : '0') === '1',
        ])
    </div>
</div>
