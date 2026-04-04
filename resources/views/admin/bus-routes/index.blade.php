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
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Operator</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Route</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Departure</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Price</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $route->operator_name }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $route->from_city }} → {{ $route->to_city }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $route->departure_at }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">Rs {{ number_format($route->price, 2) }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.bus-routes.edit', $route) }}">Edit</a>
                                <form method="post" action="{{ route('admin.bus-routes.destroy', $route) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </td>
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

