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
                @elseif($slug === 'hotels')
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">City</label><input name="city" value="{{ request('city') }}" placeholder="City"></div>
                    <div><label style="font-size:12px;font-weight:600;color:#64748b;">Hotel name</label><input name="q" value="{{ request('q') }}" placeholder="Optional"></div>
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
        </div>
        @endunless

        <div class="grid">
            @forelse($items as $item)
                <div class="card">
                    <h3 style="margin-top:0;">{{ $item['title'] }}</h3>
                    <p style="font-size:14px;color:#64748b;">{{ $item['subtitle'] }}</p>
                    <p><strong>From: Rs {{ number_format($item['price'], 2) }}</strong></p>
                    <a class="btn secondary" href="{{ route('module.show', ['module' => $slug, 'id' => $item['id']]) }}">View Details</a>
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
