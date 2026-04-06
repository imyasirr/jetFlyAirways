@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Bus Routes</h1>
            <a class="btn" href="{{ route('admin.bus-routes.create') }}">Add Route</a>
        </div>

        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Operator</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                        <tr>
                            <td>{{ $route->operator_name }}</td>
                            <td>{{ $route->from_city }} → {{ $route->to_city }}</td>
                            <td>{{ $route->departure_at }}</td>
                            <td>Rs {{ number_format($route->price, 2) }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.bus-routes.edit', $route) }}">Edit</a>
                                <form method="post" action="{{ route('admin.bus-routes.destroy', $route) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:10px;">No bus routes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ $routes->links() }}</div>
    </div>
@endsection
