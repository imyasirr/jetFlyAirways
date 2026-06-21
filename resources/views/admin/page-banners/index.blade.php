@extends('layouts.admin')

@section('content')
    <div class="card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
            <div>
                <h1 class="section-title" style="margin:0;">Page banners</h1>
                <p style="margin:6px 0 0;color:#64748b;font-size:14px;">Hero backgrounds for module pages (/flights, /hotels, …), blog, Contact us, and custom pages you add here (<code>/p/slug</code>).</p>
            </div>
            <a class="btn" href="{{ route('admin.page-banners.create') }}">Add page</a>
        </div>

        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Banner</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            <strong>{{ $banner->label }}</strong>
                            @if($banner->isCustomCmsBanner())
                                <div style="font-size:12px;color:#64748b;margin-top:4px;">Custom page — edit text under CMS pages</div>
                            @endif
                            @if(filled($banner->subtitle))
                                <div style="font-size:12px;color:#64748b;margin-top:4px;">{{ \Illuminate\Support\Str::limit($banner->subtitle, 80) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($banner->imageUrl())
                                <img src="{{ $banner->imageUrl() }}" alt="" style="width:120px;height:56px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                            @else
                                <span style="font-size:13px;color:#94a3b8;">No image — solid colour fallback</span>
                            @endif
                        </td>
                        <td>{{ $banner->is_active ? 'Active' : 'Hidden' }}</td>
                        <td class="admin-table-actions">
                            @php
                                $cmsPage = $banner->linkedCmsPage();
                            @endphp
                            @include('admin.partials.table-actions', [
                                'edit' => route('admin.page-banners.edit', $banner),
                                'editLabel' => 'Edit banner',
                                'view' => $cmsPage ? route('admin.pages.edit', $cmsPage) : null,
                                'viewLabel' => 'Edit page content',
                                'viewIcon' => 'article',
                                'delete' => $banner->isCustomCmsBanner() ? route('admin.page-banners.destroy', $banner) : null,
                                'deleteConfirm' => 'Delete this page and its banner?',
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="color:#64748b;font-size:14px;padding:24px;">
                            No page banners yet. Run <code style="font-size:13px;">php artisan migrate</code> on this server, then refresh — or click <strong>Add page</strong>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
@endsection
