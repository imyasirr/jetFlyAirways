@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Careers / vacancies</h1>
            <a class="btn" href="{{ route('admin.careers.create') }}">Add vacancy</a>
        </div>
        <p style="font-size:14px;color:#64748b;">Public listing: <a href="{{ route('jobs.index') }}" target="_blank" rel="noopener">/jobs</a></p>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Hiring</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($careers as $c)
                        <tr>
                            <td>{{ $c->job_title }}</td>
                            <td>{{ $c->location ?? '—' }}</td>
                            <td>{{ $c->is_hiring ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.careers.edit', $c) }}">Edit</a>
                                <form method="post" action="{{ route('admin.careers.destroy', $c) }}" onsubmit="return confirm('Delete?');">
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
        <div style="margin-top:12px;">{{ $careers->links() }}</div>
    </div>
@endsection

