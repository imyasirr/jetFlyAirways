@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Site SEO (homepage / welcome)</h1>
        <p style="font-size:14px;color:#64748b;">Applies to <code>/</code> and <code>/welcome</code> meta tags and Open Graph when filled.</p>
        <form method="post" action="{{ route('admin.site-seo.update') }}" enctype="multipart/form-data" style="display:grid;gap:12px;max-width:640px;margin-top:16px;">
            @csrf
            @method('PUT')
            <label>Meta title <input type="text" name="meta_title" value="{{ old('meta_title', $seo->meta_title) }}"></label>
            <label>Meta description <textarea name="meta_description" rows="3">{{ old('meta_description', $seo->meta_description) }}</textarea></label>
            <label>Keywords <input type="text" name="keywords" value="{{ old('keywords', $seo->keywords) }}"></label>
            <label>Canonical URL <input type="text" name="canonical_url" value="{{ old('canonical_url', $seo->canonical_url) }}" placeholder="https://…"></label>
            <label>Robots <input type="text" name="robots" value="{{ old('robots', $seo->robots) }}" placeholder="index,follow"></label>
            <label>OG title <input type="text" name="og_title" value="{{ old('og_title', $seo->og_title) }}"></label>
            <label>OG description <textarea name="og_description" rows="2">{{ old('og_description', $seo->og_description) }}</textarea></label>
            @include('admin.partials.image-upload', [
                'label' => 'OG image',
                'name' => 'og_image_file',
                'currentPath' => $seo->og_image,
                'required' => false,
                'hint' => 'Optional. Used for social previews. JPEG, PNG, WebP or GIF — max 10 MB.',
            ])
            <label>Schema markup (JSON-LD) <textarea name="schema_markup" rows="6" style="font-family:ui-monospace,monospace;font-size:12px;">{{ old('schema_markup', $seo->schema_markup) }}</textarea></label>
            <button type="submit" class="btn">Save</button>
        </form>
    </div>
@endsection
