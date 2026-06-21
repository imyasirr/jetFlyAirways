@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Blog posts</h1>
            <a class="btn" href="{{ route('admin.blogs.create') }}">Add post</a>
        </div>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Publish</th>
                        <th>Featured</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $b)
                        <tr>
                            <td>{{ $b->title }}</td>
                            <td><code style="font-size:12px;">{{ $b->slug }}</code></td>
                            <td>{{ $b->publish_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>{{ $b->is_featured ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'view' => route('admin.blogs.show', $b),
                                    'viewLabel' => 'Show',
                                    'edit' => route('admin.blogs.edit', $b),
                                    'delete' => route('admin.blogs.destroy', $b),
                                    'deleteConfirm' => 'Delete?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $blogs->links() }}</div>
    </div>
@endsection

