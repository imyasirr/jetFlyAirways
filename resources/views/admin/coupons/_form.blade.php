@php $c = $coupon ?? null; @endphp
<div style="display:grid;gap:14px;max-width:480px;">
    <div>
        <label>Code</label>
        <input type="text" name="code" value="{{ old('code', $c?->code) }}" required maxlength="40">
    </div>
    <div>
        <label>Discount type</label>
        <select name="discount_type" required>
            <option value="flat" @selected(old('discount_type', $c?->discount_type) === 'flat')>Flat (₹)</option>
            <option value="percent" @selected(old('discount_type', $c?->discount_type) === 'percent')>Percent (%)</option>
        </select>
    </div>
    <div>
        <label>Value</label>
        <input type="text" name="discount_value" value="{{ old('discount_value', $c?->discount_value) }}" required>
    </div>
    <div>
        <label>Valid from</label>
        <input type="date" name="valid_from" value="{{ old('valid_from', $c?->valid_from?->format('Y-m-d')) }}">
    </div>
    <div>
        <label>Valid to</label>
        <input type="date" name="valid_to" value="{{ old('valid_to', $c?->valid_to?->format('Y-m-d')) }}">
    </div>
    <div>
        <label>Max usage (optional)</label>
        <input type="number" name="max_usage" value="{{ old('max_usage', $c?->max_usage) }}" min="0">
    </div>
    <div>
        <label>Used count</label>
        <input type="number" name="used_count" value="{{ old('used_count', $c?->used_count ?? 0) }}" min="0">
    </div>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $c?->is_active ?? true))> Active
    </label>
</div>
