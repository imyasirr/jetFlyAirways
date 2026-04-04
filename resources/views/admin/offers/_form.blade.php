@php $o = $offer ?? null; @endphp
<div class="admin-form-grid">
    <label>Title
        <input type="text" name="title" value="{{ old('title', $o?->title) }}" required>
    </label>
    <label>Redirect URL
        <input type="text" name="redirect_url" value="{{ old('redirect_url', $o?->redirect_url) }}" placeholder="https://… or /flights">
    </label>
    <label>Start date
        <input type="date" name="start_date" value="{{ old('start_date', $o?->start_date?->format('Y-m-d')) }}">
    </label>
    <label>End date
        <input type="date" name="end_date" value="{{ old('end_date', $o?->end_date?->format('Y-m-d')) }}">
    </label>
    <label class="admin-field-full">Description
        <textarea name="description" rows="4">{{ old('description', $o?->description) }}</textarea>
    </label>
    <div class="admin-field-full">
        @include('admin.partials.toggle', [
            'name' => 'is_active',
            'label' => 'Active',
            'hint' => 'Inactive offers are not shown in offer strips or homepage modules.',
            'checked' => old('is_active', ($o?->is_active ?? true) ? '1' : '0') === '1',
        ])
    </div>
</div>
