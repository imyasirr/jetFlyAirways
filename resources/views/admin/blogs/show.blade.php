@extends('layouts.admin')

@section('title', $blog->title)

@section('page_description', 'Preview how this post looks on the site, SEO fields, and publishing status.')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:16px;align-items:flex-start;">
            <div>
                <h1 class="section-title" style="margin:0 0 6px;">{{ $blog->title }}</h1>
                <p style="margin:0;font-size:13px;color:#64748b;">
                    <code style="font-size:12px;">{{ $blog->slug }}</code>
                    @if($isPublished)
                        <span style="margin-left:8px;padding:2px 8px;border-radius:999px;background:rgba(34,197,94,.15);color:#15803d;font-weight:700;font-size:11px;">Live</span>
                    @elseif($blog->publish_at && $blog->publish_at->isFuture())
                        <span style="margin-left:8px;padding:2px 8px;border-radius:999px;background:rgba(234,179,8,.2);color:#a16207;font-weight:700;font-size:11px;">Scheduled</span>
                    @else
                        <span style="margin-left:8px;padding:2px 8px;border-radius:999px;background:rgba(148,163,184,.25);color:#475569;font-weight:700;font-size:11px;">Draft</span>
                    @endif
                </p>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end;">
                @if($isPublished)
                    <a class="btn secondary" href="{{ route('blog.show', $blog) }}" target="_blank" rel="noopener">View on site</a>
                @endif
                <a class="btn secondary" href="{{ route('admin.blogs.edit', $blog) }}">Edit</a>
                <a class="btn ghost" href="{{ route('admin.blogs.index') }}">← All posts</a>
            </div>
        </div>

        <div class="admin-form-grid" style="margin-bottom:20px;">
            <div>
                <p style="margin:0 0 4px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Publish at</p>
                <p style="margin:0;font-size:14px;font-weight:600;">{{ $blog->publish_at?->format('l, d M Y H:i') ?? '—' }}</p>
            </div>
            <div>
                <p style="margin:0 0 4px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Featured</p>
                <p style="margin:0;font-size:14px;font-weight:600;">{{ $blog->is_featured ? 'Yes' : 'No' }}</p>
            </div>
            <div>
                <p style="margin:0 0 4px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Category</p>
                <p style="margin:0;font-size:14px;font-weight:600;">{{ $blog->category ?: '—' }}</p>
            </div>
            <div>
                <p style="margin:0 0 4px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Author</p>
                <p style="margin:0;font-size:14px;font-weight:600;">{{ $blog->author_name ?: '—' }}</p>
            </div>
            <div class="admin-field-full">
                <p style="margin:0 0 4px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Tags</p>
                <p style="margin:0;font-size:14px;">{{ $blog->tags ?: '—' }}</p>
            </div>
            <div class="admin-field-full">
                <p style="margin:0 0 4px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Meta title</p>
                <p style="margin:0;font-size:14px;">{{ $blog->meta_title ?: '—' }}</p>
            </div>
            <div class="admin-field-full">
                <p style="margin:0 0 4px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Meta description</p>
                <p style="margin:0;font-size:14px;color:#475569;line-height:1.5;">{{ $blog->meta_description ?: '—' }}</p>
            </div>
        </div>

        @if($blog->cover_url)
            <p style="margin:0 0 8px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Cover image</p>
            <div style="border-radius:12px;overflow:hidden;border:1px solid var(--admin-border);max-width:720px;margin-bottom:20px;">
                <img src="{{ $blog->cover_url }}" alt="" style="width:100%;height:auto;display:block;max-height:360px;object-fit:cover;">
            </div>
        @endif

        <p style="margin:0 0 8px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Content preview</p>
        <div class="admin-blog-preview-body">
            {!! $blog->rendered_content ?: '<p style="color:#64748b;margin:0;">No body content yet.</p>' !!}
        </div>
    </div>
@endsection
