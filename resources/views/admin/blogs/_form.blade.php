@php $b = $blog ?? null; @endphp
<div style="display:grid;gap:12px;max-width:720px;">
    <label>Title <input type="text" name="title" value="{{ old('title', $b?->title) }}" required></label>
    <p style="margin:0;font-size:13px;color:#64748b;line-height:1.45;">Public URL slug is generated from the title automatically (editing the title updates the slug).</p>
    <label>Category <input type="text" name="category" value="{{ old('category', $b?->category) }}"></label>
    <label>Tags (comma-separated) <input type="text" name="tags" value="{{ old('tags', $b?->tags) }}"></label>
    <label>Author <input type="text" name="author_name" value="{{ old('author_name', $b?->author_name) }}"></label>
    @include('admin.partials.image-upload', [
        'label' => 'Cover image',
        'name' => 'cover_image_file',
        'currentPath' => $b?->cover_image,
        'required' => false,
        'hint' => 'Optional. JPEG, PNG, WebP or GIF — max 10 MB.',
    ])
    <label>Meta title <input type="text" name="meta_title" value="{{ old('meta_title', $b?->meta_title) }}"></label>
    <label>Meta description <textarea name="meta_description" rows="2">{{ old('meta_description', $b?->meta_description) }}</textarea></label>
    <label>Content <textarea name="content" rows="14" style="font-family:ui-monospace,monospace;font-size:13px;">{{ old('content', $b?->content) }}</textarea></label>
    <label>Publish at <input type="datetime-local" name="publish_at" value="{{ old('publish_at', $b?->publish_at?->format('Y-m-d\TH:i')) }}"></label>
    @include('admin.partials.toggle', [
        'name' => 'is_featured',
        'label' => 'Featured',
        'hint' => 'Featured posts can be highlighted in blog listings (if your theme uses this flag).',
        'checked' => old('is_featured', ($b?->is_featured ?? false) ? '1' : '0') === '1',
    ])
</div>
