@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Contact inquiries</h1>
        <p style="font-size:14px;color:#64748b;">Messages from <a href="{{ route('contact.create') }}" target="_blank" rel="noopener">/contact-us</a></p>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $i)
                        <tr>
                            <td>{{ $i->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $i->name }}<br><small style="color:#64748b;">{{ $i->email }}</small></td>
                            <td>{{ \Illuminate\Support\Str::limit($i->subject ?? '—', 40) }}</td>
                            <td>{{ $i->status }}</td>
                            <td><a class="btn secondary" href="{{ route('admin.contact-inquiries.show', $i) }}">Open</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $inquiries->links() }}</div>
    </div>
@endsection

