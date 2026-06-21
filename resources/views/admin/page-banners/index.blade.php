@extends('layouts.admin')

@section('content')
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
            <div>
                <h1 class="section-title" style="margin:0;">Page banners</h1>
                <p style="margin:6px 0 0;color:#64748b;font-size:14px;">Hero background images for module listing pages and the travel blog. CMS pages use the banner field on each page edit screen.</p>
            </div>
        </div>

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
                @foreach($banners as $banner)
                    <tr>
                        <td>
                            <strong>{{ $banner->label }}</strong>
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
                            @include('admin.partials.table-actions', [
                                'edit' => route('admin.page-banners.edit', $banner),
                                'editLabel' => 'Edit banner',
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
