@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add page</h1>
        <p style="margin:0 0 16px;color:#64748b;">Creates a public CMS page at <code>/p/your-slug</code> and its hero banner entry. Edit page text later under <strong>CMS pages</strong>.</p>

        <form method="post" action="{{ route('admin.page-banners.store') }}" enctype="multipart/form-data" class="admin-form-grid">
            @csrf

            <label>Page title
                <input type="text" name="title" value="{{ old('title') }}" required maxlength="200" placeholder="About us">
            </label>

            <label>URL slug
                <input type="text" name="slug" value="{{ old('slug') }}" required pattern="[a-z0-9]+(-[a-z0-9]+)*" maxlength="80" placeholder="about-us">
                <span style="font-size:12px;color:#64748b;">Public URL: <code>/p/about-us</code> — lowercase, hyphens only.</span>
            </label>

            <label class="admin-field-full">Hero subtitle (optional)
                <textarea name="subtitle" rows="3" maxlength="500" placeholder="Short line under the page title">{{ old('subtitle') }}</textarea>
            </label>

            <div class="admin-field-full">
                @include('admin.partials.image-upload', [
                    'label' => 'Banner image',
                    'name' => 'image_file',
                    'currentPath' => null,
                    'required' => false,
                    'hint' => 'Wide image around 1800×600 works best.',
                ])
            </div>

            @include('admin.partials.toggle', [
                'name' => 'is_active',
                'label' => 'Published',
                'hint' => 'Draft pages stay hidden on the public site.',
                'checked' => old('is_active', '1') === '1',
            ])

            <div class="admin-field-full" style="display:flex;gap:10px;flex-wrap:wrap;">
                <button type="submit" class="btn">Create page</button>
                <a class="btn secondary" href="{{ route('admin.page-banners.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
