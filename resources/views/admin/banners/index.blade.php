@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Homepage banners</h1>
            <a class="btn" href="{{ route('admin.banners.create') }}">Add banner</a>
        </div>
        <div style="overflow:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Title</th>
                        <th>Order</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td>
                                @if($u = \App\Support\PublicImageStorage::url($banner->image))
                                    <img src="{{ $u }}" alt="" style="width:80px;height:40px;object-fit:cover;border-radius:6px;">
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $banner->title ?: '—' }}</td>
                            <td>{{ $banner->sort_order }}</td>
                            <td>{{ $banner->is_active ? 'Yes' : 'No' }}</td>
                            <td style="display:flex;gap:8px;flex-wrap:wrap;">
                                <a class="btn secondary" href="{{ route('admin.banners.edit', $banner) }}">Edit</a>
                                <form method="post" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Delete this banner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $banners->links() }}</div>
    </div>
@endsection
