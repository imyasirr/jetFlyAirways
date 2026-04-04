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
    <div class="cms-editor-wrap">
        <label for="cms_page_body">Page content</label>
        <textarea id="cms_page_body" name="body" rows="18" required>{{ old('body', $p?->body) }}</textarea>
        <p class="cms-editor-hint">Rich text editor — paste from Word or Google Docs keeps most formatting. Use “Source code” for raw HTML. Public page uses the same colours and fonts as your site.</p>
    </div>
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Published',
        'hint' => 'Draft pages stay hidden; visitors only see published pages at /p/your-slug.',
        'checked' => old('is_active', ($p?->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>
@include('admin.partials.tinymce-cms-page')
