@php $p = $page ?? null; @endphp
<div style="display:grid;gap:14px;max-width:720px;">
    <div>
        <label>URL slug</label>
        <input type="text" name="slug" value="{{ old('slug', $p?->slug) }}" required pattern="[a-z0-9]+(-[a-z0-9]+)*" placeholder="about-us">
        <span style="font-size:12px;color:#64748b;">Public URL: <code>/p/your-slug</code>. Lowercase, hyphens only. Cannot use reserved names (flights, admin, …).</span>
    </div>
    <div>
        <label>Page title</label>
        <input type="text" name="title" value="{{ old('title', $p?->title) }}" required maxlength="200">
    </div>
    <div>
        <label>Meta description (SEO)</label>
        <input type="text" name="meta_description" value="{{ old('meta_description', $p?->meta_description) }}" maxlength="500" placeholder="Optional — shown in search snippets">
    </div>
    <div>
        <label>Body (HTML allowed)</label>
        <textarea name="body" rows="22" required style="font-family:ui-monospace,monospace;font-size:13px;">{{ old('body', $p?->body) }}</textarea>
    </div>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p?->is_active ?? true))> Published
    </label>
</div>
