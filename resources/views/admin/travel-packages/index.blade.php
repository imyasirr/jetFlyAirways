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

        <div style="overflow:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Name</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Category</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Destination</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Days</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Price</th>
                        <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $package->name }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $package->category }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $package->destination }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">{{ $package->duration_days }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">Rs {{ number_format($package->price, 2) }}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.travel-packages.edit', $package) }}">Edit</a>
                                <form method="post" action="{{ route('admin.travel-packages.destroy', $package) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
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
