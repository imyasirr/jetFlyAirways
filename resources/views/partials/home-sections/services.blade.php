<h2 class="section-title section-title-spaced-lg">All travel services</h2>
<div class="grid">
    @foreach($modules as $slug => $module)
        <div class="card">
            <h3 class="card-title-lg">{{ $module['icon'] }} {{ $module['title'] }}</h3>
            <ul class="card-list">
                @foreach(array_slice($module['features'], 0, 3) as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
            <a class="btn btn-block" href="{{ route('module.index', $slug) }}">Explore</a>
        </div>
    @endforeach
</div>
