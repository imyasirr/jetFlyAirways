@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">CMS pages</h1>
            <a class="btn" href="{{ route('admin.pages.create') }}">New page</a>
        </div>
        <p style="color:#64748b;font-size:14px;">Content is shown at <code>/p/{slug}</code>. Update menus separately under <a href="{{ route('admin.menu-items.index') }}">Header &amp; footer menu</a> if URLs change.</p>
        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif
        <div style="overflow:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Slug</th>
                        <th>Title</th>
                        <th>URL</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $pg)
                        <tr>
                            <td><code>{{ $pg->slug }}</code></td>
                            <td>{{ $pg->title }}</td>
                            <td><a href="{{ url('/p/'.$pg->slug) }}" target="_blank" rel="noopener">{{ url('/p/'.$pg->slug) }}</a></td>
                            <td>{{ $pg->is_active ? 'Yes' : 'No' }}</td>
                            <td style="display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.pages.edit', $pg) }}">Edit</a>
                                <form method="post" action="{{ route('admin.pages.destroy', $pg) }}" onsubmit="return confirm('Delete this page?');">
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
        <div style="margin-top:12px;">{{ $pages->links() }}</div>
    </div>
@endsection
