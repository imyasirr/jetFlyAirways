@extends('layouts.app')

@section('content')
    <div class="card" style="margin-bottom:16px;">
        <h1 class="section-title">{{ $module['icon'] }} {{ $module['title'] }}</h1>
        <p style="margin:0;color:#64748b;">
            @if(!empty($addonCatalog))
                {{ __('jetfly.module_addon_intro') }}
            @else
                {{ __('jetfly.module_db_intro') }}
            @endif
        </p>
    </div>

    @if(!empty($staticModule))
        <div class="card">
            <p>{{ __('jetfly.module_static_addon') }}</p>
            <p style="margin-top:12px;"><a class="btn secondary" href="{{ route('contact.create') }}">{{ __('jetfly.contact_us') }}</a>
                <a class="btn" href="{{ route('home') }}" style="margin-left:8px;">{{ __('jetfly.back_home') }}</a></p>
        </div>
    @else
        @unless(!empty($addonCatalog))
        <div class="card" style="margin-bottom:16px;">
            <form method="get" action="{{ route('module.index', $slug) }}" class="search-grid">
                @if($slug === 'flights')
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">From</label><input name="from" value="{{ request('from') }}" placeholder="City"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">To</label><input name="to" value="{{ request('to') }}" placeholder="City"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Date</label><input type="date" name="date" value="{{ request('date') }}"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Airline</label><input name="airline" value="{{ request('airline') }}" placeholder="Name"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Cabin</label>
                        <select name="cabin_class">
                            <option value="">Any</option>
                            <option value="Economy" @selected(request('cabin_class') === 'Economy')>Economy</option>
                            <option value="Premium Economy" @selected(request('cabin_class') === 'Premium Economy')>Premium Economy</option>
                            <option value="Business" @selected(request('cabin_class') === 'Business')>Business</option>
                            <option value="First" @selected(request('cabin_class') === 'First')>First</option>
                        </select>
                    </div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Max stops</label><input type="number" name="stops" min="0" max="9" value="{{ request('stops') }}" placeholder="e.g. 1"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Min ₹</label><input type="number" name="min_price" min="0" step="1" value="{{ request('min_price') }}"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Max ₹</label><input type="number" name="max_price" min="0" step="1" value="{{ request('max_price') }}"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Dep after</label><input type="time" name="dep_after" value="{{ request('dep_after') }}"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Dep before</label><input type="time" name="dep_before" value="{{ request('dep_before') }}"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Max duration (min)</label><input type="number" name="max_duration_mins" min="15" value="{{ request('max_duration_mins') }}" placeholder="e.g. 180"></div>
                @elseif($slug === 'hotels')
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">City</label><input name="city" value="{{ request('city') }}" placeholder="City"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Hotel name</label><input name="q" value="{{ request('q') }}" placeholder="Optional"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Min ₹ / night</label><input type="number" name="min_price" min="0" value="{{ request('min_price') }}"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Max ₹ / night</label><input type="number" name="max_price" min="0" value="{{ request('max_price') }}"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Min stars</label><input type="number" name="min_stars" min="1" max="5" value="{{ request('min_stars') }}"></div>
                @elseif($slug === 'packages')
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Destination</label><input name="destination" value="{{ request('destination') }}" placeholder="Place"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Category</label><input name="category" value="{{ request('category') }}" placeholder="Type"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Keyword</label><input name="q" value="{{ request('q') }}" placeholder="Search"></div>
                @elseif(in_array($slug, ['buses','trains']))
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">From</label><input name="from" value="{{ request('from') }}" placeholder="City"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">To</label><input name="to" value="{{ request('to') }}" placeholder="City"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Date</label><input type="date" name="date" value="{{ request('date') }}"></div>
                @elseif($slug === 'cabs')
                    <div style="grid-column:span 2;"><label style="font-size:12px;font-weight:600;color:#64748b;">Search</label><input name="q" value="{{ request('q') }}" placeholder="Service type, from, to…"></div>
                @endif
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <button class="btn secondary" type="submit">Apply filters</button>
                    <a class="btn" href="{{ route('module.index', $slug) }}">Clear</a>
                </div>
            </form>
            @if($slug === 'hotels' && (request('check_in') || request('check_out') || request('guests')))
                <p style="margin:10px 0 0;font-size:13px;color:#64748b;">Check-in / guests from home search are shown for planning — listing filters use price &amp; stars.</p>
            @endif
        </div>
        @endunless

        @if(empty($addonCatalog) && $slug === 'trains')
            <div class="card" style="margin-bottom:16px;">
                <h2 class="section-title" style="font-size:1rem;">PNR status (demo)</h2>
                <p style="margin:0 0 10px;font-size:13px;color:#64748b;">Enter a PNR to see a sample response. Live data needs a rail API later.</p>
                <form method="get" action="{{ route('module.index', 'trains') }}" class="search-grid" style="align-items:flex-end;">
                    @foreach(request()->except('pnr') as $key => $val)
                        @if(is_array($val)) @continue @endif
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">PNR</label><input name="pnr" value="{{ request('pnr') }}" placeholder="e.g. 4258123456" maxlength="12"></div>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn secondary">Check PNR</button>
                    </div>
                </form>
                @if(!empty($trainPnrResult))
                    <div style="margin-top:12px;padding:12px;border-radius:10px;border:1px solid var(--border);background:var(--card);font-size:14px;">
                        @if(!empty($trainPnrResult['ok']))
                            <p style="margin:0 0 6px;"><strong>PNR {{ $trainPnrResult['pnr'] }}</strong></p>
                            <p style="margin:0;color:#64748b;">{{ $trainPnrResult['message'] }}</p>
                            <p style="margin:8px 0 0;"><strong>Train:</strong> {{ $trainPnrResult['train'] ?? '—' }}</p>
                            <p style="margin:4px 0 0;"><strong>Route:</strong> {{ $trainPnrResult['from'] ?? '—' }} → {{ $trainPnrResult['to'] ?? '—' }}</p>
                            <p style="margin:4px 0 0;"><strong>Status:</strong> {{ $trainPnrResult['status'] ?? '—' }}</p>
                        @else
                            <p style="margin:0;color:#b45309;">{{ $trainPnrResult['message'] }}</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <div class="grid">
            @forelse($items as $item)
                <div class="card">
                    <h3 style="margin-top:0;">{{ $item['title'] }}</h3>
                    <p style="font-size:14px;color:#64748b;">{{ $item['subtitle'] }}</p>
                    @if(($item['price'] ?? 0) > 0)
                        <p><strong>From: Rs {{ number_format($item['price'], 2) }}</strong></p>
                    @endif
                    @if(!empty($item['external']))
                        <a class="btn secondary" href="{{ $item['book_url'] ?? route('contact.create') }}">Request booking</a>
                    @else
                        <a class="btn secondary" href="{{ route('module.show', ['module' => $slug, 'item' => $item['slug']]) }}">View Details</a>
                    @endif
                </div>
            @empty
                <div class="card" style="grid-column:1/-1;">
                    <p>No matching listings. Try clearing filters or add data in the <a href="{{ route('admin.dashboard') }}">admin panel</a>.</p>
                </div>
            @endforelse
        </div>
        @if($items->hasPages())
            <div style="margin-top:16px;">{{ $items->links() }}</div>
        @endif
    @endif
@endsection
