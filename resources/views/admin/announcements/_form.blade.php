@php /** @var \App\Models\Announcement|null $announcement */ @endphp
<label>Title</label>
<input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required maxlength="200">

<label>Body (optional)</label>
<textarea name="body" rows="6" maxlength="10000">{{ old('body', $announcement->body ?? '') }}</textarea>

<label>External link (optional)</label>
<input type="url" name="link" value="{{ old('link', $announcement->link ?? '') }}" maxlength="500">

<label>Published at</label>
<input type="datetime-local" name="published_at" value="{{ old('published_at', isset($announcement) && $announcement->published_at ? $announcement->published_at->timezone(config('app.timezone'))->format('Y-m-d\TH:i') : '') }}">

<label style="display:flex;align-items:center;gap:8px;font-weight:600;">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active', ($announcement->is_active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
    Active
</label>
