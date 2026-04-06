@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Announcements</h1>
            <a class="btn" href="{{ route('admin.announcements.create') }}">New announcement</a>
        </div>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Published</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($announcements as $a)
                        <tr>
                            <td>{{ \Illuminate\Support\Str::limit($a->title, 60) }}</td>
                            <td>{{ $a->published_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>{{ $a->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.announcements.edit', $a) }}">Edit</a>
                                <form method="post" action="{{ route('admin.announcements.destroy', $a) }}" onsubmit="return confirm('Delete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $announcements->links() }}</div>
    </div>
@endsection

