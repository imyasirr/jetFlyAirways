@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Users</h1>
        <p style="color:#64748b;font-size:14px;">Customer accounts registered on the site. Admins can see bookings linked to each user.</p>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Admin</th>
                        <th>Bookings</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->phone ?? '—' }}</td>
                            <td>{{ $u->is_admin ? 'Yes' : 'No' }}</td>
                            <td>{{ $u->bookings_count }}</td>
                            <td><a class="btn secondary" href="{{ route('admin.users.show', $u) }}">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $users->links() }}</div>
    </div>
@endsection

