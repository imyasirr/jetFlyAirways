@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Blog posts</h1>
            <a class="btn" href="{{ route('admin.blogs.create') }}">Add post</a>
        </div>
        <div style="overflow:auto;">
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
                            <td style="display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.blogs.edit', $b) }}">Edit</a>
                                <form method="post" action="{{ route('admin.blogs.destroy', $b) }}" onsubmit="return confirm('Delete?');">
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
        <div style="margin-top:12px;">{{ $blogs->links() }}</div>
    </div>
@endsection
