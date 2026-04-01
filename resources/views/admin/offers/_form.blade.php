@php $o = $offer ?? null; @endphp
<div style="display:grid;gap:12px;max-width:560px;">
    <label>Title <input type="text" name="title" value="{{ old('title', $o?->title) }}" required></label>
    <label>Description <textarea name="description" rows="4">{{ old('description', $o?->description) }}</textarea></label>
    <label>Redirect URL <input type="text" name="redirect_url" value="{{ old('redirect_url', $o?->redirect_url) }}" placeholder="https://… or /flights"></label>
    <label>Start date <input type="date" name="start_date" value="{{ old('start_date', $o?->start_date?->format('Y-m-d')) }}"></label>
    <label>End date <input type="date" name="end_date" value="{{ old('end_date', $o?->end_date?->format('Y-m-d')) }}"></label>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $o?->is_active ?? true))> Active
    </label>
</div>
