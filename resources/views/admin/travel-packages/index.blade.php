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
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.travel-packages.edit', $package) }}">Edit</a>
                                <form method="post" action="{{ route('admin.travel-packages.destroy', $package) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </div></td>
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
