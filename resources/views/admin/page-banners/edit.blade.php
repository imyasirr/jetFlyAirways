@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit page banner</h1>
        <p style="margin:0 0 8px;color:#64748b;">{{ $pageBanner->label }}</p>
        @if($cmsPage = $pageBanner->linkedCmsPage())
            <p style="margin:0 0 16px;font-size:13px;color:#64748b;">
                Page content:
                <a href="{{ route('admin.pages.edit', $cmsPage) }}" style="color:var(--admin-accent);font-weight:600;">Edit in CMS pages</a>
                · Public:
                <a href="{{ route('pages.show', $cmsPage->slug) }}" target="_blank" rel="noopener" style="color:var(--admin-accent);">/p/{{ $cmsPage->slug }}</a>
            </p>
        @else
            <p style="margin:0 0 16px;color:#64748b;">Built-in module page — banner only (no CMS content).</p>
        @endif

        <form method="post" action="{{ route('admin.page-banners.update', $pageBanner) }}" enctype="multipart/form-data" class="admin-form-grid">
            @csrf
            @method('PUT')

            <label class="admin-field-full">Hero subtitle (optional)
                <textarea name="subtitle" rows="3" maxlength="500" placeholder="Optional line under the page title on the public site">{{ old('subtitle', $pageBanner->subtitle) }}</textarea>
                <span style="font-size:12px;color:#64748b;">Leave blank to use the default module description.</span>
            </label>

            <div class="admin-field-full">
                @include('admin.partials.image-upload', [
                    'label' => 'Banner image',
                    'name' => 'image_file',
                    'currentPath' => $pageBanner->image ?? null,
                    'required' => false,
                    'hint' => 'Wide image around 1800×600 works best. Shown behind the page title with a colour overlay.',
                ])
            </div>

            @include('admin.partials.toggle', [
                'name' => 'is_active',
                'label' => 'Active',
                'hint' => 'When hidden, the page uses the default solid colour hero only.',
                'checked' => old('is_active', $pageBanner->is_active ? '1' : '0') === '1',
            ])

            <div class="admin-field-full" style="display:flex;gap:10px;flex-wrap:wrap;">
                <button type="submit" class="btn">Save banner</button>
                <a class="btn secondary" href="{{ route('admin.page-banners.index') }}">Back</a>
            </div>
        </form>
    </div>
@endsection
