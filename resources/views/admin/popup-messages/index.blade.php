@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Welcome popups</h1>
            <a class="btn" href="{{ route('admin.popup-messages.create') }}">Add popup</a>
        </div>
        <div style="overflow:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Dates</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($popups as $p)
                        <tr>
                            <td>{{ $p->title ?: '—' }}</td>
                            <td>{{ $p->start_date?->format('Y-m-d') ?? '—' }} → {{ $p->end_date?->format('Y-m-d') ?? '—' }}</td>
                            <td>{{ $p->is_active ? 'Yes' : 'No' }}</td>
                            <td style="display:flex;gap:8px;flex-wrap:wrap;">
                                <a class="btn secondary" href="{{ route('admin.popup-messages.edit', $p) }}">Edit</a>
                                <form method="post" action="{{ route('admin.popup-messages.destroy', $p) }}" onsubmit="return confirm('Delete?');">
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
        <div style="margin-top:12px;">{{ $popups->links() }}</div>
    </div>
@endsection
