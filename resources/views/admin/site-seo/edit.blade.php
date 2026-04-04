@extends('layouts.admin')

@section('title', 'Site SEO')

@section('content')
    <div class="card">
        <h2 class="section-title" style="font-size:1.1rem;">Meta tags &amp; social preview</h2>
        <p style="font-size:14px;color:#64748b;margin:0 0 16px;max-width:62ch;line-height:1.5;">These fields apply to the <strong>home</strong> and <strong>welcome</strong> URLs (<code>/</code> and <code>/welcome</code>): browser title, description, Open Graph, and optional JSON-LD.</p>
        <form method="post" action="{{ route('admin.site-seo.update') }}" enctype="multipart/form-data" class="admin-form-grid">
            @csrf
            @method('PUT')
            <label>Meta title
                <input type="text" name="meta_title" value="{{ old('meta_title', $seo->meta_title) }}">
            </label>
            <label>Keywords
                <input type="text" name="keywords" value="{{ old('keywords', $seo->keywords) }}">
            </label>
            <label class="admin-field-full">Meta description
                <textarea name="meta_description" rows="3">{{ old('meta_description', $seo->meta_description) }}</textarea>
            </label>
            <label>Canonical URL
                <input type="text" name="canonical_url" value="{{ old('canonical_url', $seo->canonical_url) }}" placeholder="https://…">
            </label>
            <label>Robots
                <input type="text" name="robots" value="{{ old('robots', $seo->robots) }}" placeholder="index,follow">
            </label>
            <label>OG title
                <input type="text" name="og_title" value="{{ old('og_title', $seo->og_title) }}">
            </label>
            <label class="admin-field-full">OG description
                <textarea name="og_description" rows="2">{{ old('og_description', $seo->og_description) }}</textarea>
            </label>
            <div class="admin-field-full">
                @include('admin.partials.image-upload', [
                    'label' => 'OG image',
                    'name' => 'og_image_file',
                    'currentPath' => $seo->og_image,
                    'required' => false,
                    'hint' => 'Optional. Used for social previews. JPEG, PNG, WebP or GIF — max 10 MB.',
                ])
            </div>
            <label class="admin-field-full">Schema markup (JSON-LD)
                <textarea name="schema_markup" rows="6" style="font-family:ui-monospace,monospace;font-size:12px;">{{ old('schema_markup', $seo->schema_markup) }}</textarea>
            </label>
            <div class="admin-field-full" style="margin-top:4px;">
                <button type="submit" class="btn">Save</button>
            </div>
        </form>
    </div>
@endsection
