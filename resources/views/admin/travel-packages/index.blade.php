@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Holiday Packages</h1>
            <a class="btn" href="{{ route('admin.travel-packages.create') }}">Add Package</a>
        </div>

        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Destination</th>
                        <th>Days</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->category }}</td>
                            <td>{{ $package->destination }}</td>
                            <td>{{ $package->duration_days }}</td>
                            <td>Rs {{ number_format($package->price, 2) }}</td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.travel-packages.edit', $package),
                                    'delete' => route('admin.travel-packages.destroy', $package),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="padding:10px;">No packages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px;">{{ $packages->links() }}</div>
    </div>
@endsection
