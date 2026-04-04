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
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Type</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">From → To</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Base Fare</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Per KM</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $service->service_type }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $service->from_location }} → {{ $service->to_location ?? '—' }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">Rs {{ number_format($service->base_fare, 2) }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $service->per_km_rate !== null ? number_format($service->per_km_rate, 2) : '—' }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.cab-services.edit', $service) }}">Edit</a>
                                <form method="post" action="{{ route('admin.cab-services.destroy', $service) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </td>
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

