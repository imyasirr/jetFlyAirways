@php $b = $blog ?? null; @endphp
<div style="display:grid;gap:12px;max-width:720px;">
    <label>Title <input type="text" name="title" value="{{ old('title', $b?->title) }}" required></label>
    <label>Slug <input type="text" name="slug" value="{{ old('slug', $b?->slug) }}" placeholder="auto from title if empty"></label>
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
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $b?->is_featured ?? false))> Featured
    </label>
</div>
