@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Cab Services</h1>
            <a class="btn" href="{{ route('admin.cab-services.create') }}">Add Service</a>
        </div>

        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>From → To</th>
                        <th>Base Fare</th>
                        <th>Per KM</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $service->service_type }}</td>
                            <td>{{ $service->from_location }} → {{ $service->to_location ?? '—' }}</td>
                            <td>Rs {{ number_format($service->base_fare, 2) }}</td>
                            <td>{{ $service->per_km_rate !== null ? number_format($service->per_km_rate, 2) : '—' }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.cab-services.edit', $service) }}">Edit</a>
                                <form method="post" action="{{ route('admin.cab-services.destroy', $service) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:10px;">No cab services yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ $services->links() }}</div>
    </div>
@endsection
