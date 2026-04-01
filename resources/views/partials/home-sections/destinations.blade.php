<div style="margin-top:8px;">
    <h2 class="section-title">Popular destinations</h2>
    <div class="grid grid-tight">
        @forelse($topDestinations as $dest)
            <a class="card card-link" href="{{ route('module.index', 'packages') }}?destination={{ urlencode($dest) }}">{{ $dest }}</a>
        @empty
            <p class="card empty-hint" style="grid-column:1/-1;">Add packages in admin to show destinations here.</p>
        @endforelse
    </div>
</div>
