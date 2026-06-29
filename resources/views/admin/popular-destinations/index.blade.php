@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <div>
                <h1 class="section-title" style="margin:0;">Popular destinations</h1>
                <p style="margin:6px 0 0;color:var(--admin-muted);font-size:14px;">Homepage destination cards with banner, gallery and detail pages in the app.</p>
            </div>
            <a class="btn" href="{{ route('admin.popular-destinations.create') }}">Add destination</a>
        </div>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Banner</th>
                        <th>Name</th>
                        <th>Gallery</th>
                        <th>Sort</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $destination)
                        <tr>
                            <td>
                                @if($destination->bannerUrl())
                                    <img src="{{ $destination->bannerUrl() }}" alt="" style="width:72px;height:48px;object-fit:cover;border-radius:8px;">
                                @else
                                    <span style="color:var(--admin-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $destination->name }}</strong>
                                <div style="font-size:12px;color:var(--admin-muted);">{{ $destination->tag_line ?: $destination->slug }}</div>
                            </td>
                            <td>{{ $destination->gallery_count }} photos</td>
                            <td>{{ $destination->sort_order }}</td>
                            <td>{{ $destination->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions">
                                @if($destination->is_active)
                                    <a href="{{ route('destinations.show', $destination->slug) }}" target="_blank" rel="noopener" style="font-size:12px;margin-right:8px;">View</a>
                                @endif
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.popular-destinations.edit', $destination),
                                    'delete' => route('admin.popular-destinations.destroy', $destination),
                                    'deleteConfirm' => 'Delete this destination?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="color:var(--admin-muted);">No destinations yet. Add your first popular destination.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
