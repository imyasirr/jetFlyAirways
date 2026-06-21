@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Train Routes</h1>
            <a class="btn" href="{{ route('admin.train-routes.create') }}">Add Route</a>
        </div>

        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Train</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                        <tr>
                            <td>{{ $route->train_name }} ({{ $route->train_number }})</td>
                            <td>{{ $route->from_city }} → {{ $route->to_city }}</td>
                            <td>{{ $route->departure_at }}</td>
                            <td>Rs {{ number_format($route->price, 2) }}</td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.train-routes.edit', $route),
                                    'delete' => route('admin.train-routes.destroy', $route),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:10px;">No train routes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
@endsection
