@php
    use App\Support\AdminNavigation;
    $navGroups = AdminNavigation::groups();
    $activeGroup = AdminNavigation::activeGroup();
@endphp
<div class="admin-drawer-backdrop" data-admin-drawer-close aria-hidden="true"></div>
<aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin navigation">
    <div class="admin-sidebar-brand">
        <div class="admin-sidebar-brand__text">
            Jet Fly
            <span>Admin panel</span>
        </div>
        <button type="button" class="admin-drawer-close" data-admin-drawer-close aria-label="Close menu">
            <span class="material-symbols-outlined" aria-hidden="true">close</span>
        </button>
    </div>

    <div class="admin-sidebar-panels">
        <nav class="admin-sidebar-rail" aria-label="Main sections">
            @foreach($navGroups as $group)
                <a
                    href="{{ AdminNavigation::groupHref($group) }}"
                    class="admin-rail-link {{ AdminNavigation::groupIsActive($group) ? 'is-active' : '' }}"
                    title="{{ $group['label'] }}"
                    @if(AdminNavigation::groupIsActive($group)) aria-current="true" @endif
                >
                    <span class="material-symbols-outlined admin-rail-icon" aria-hidden="true">{{ $group['icon'] }}</span>
                    <span class="admin-rail-label">{{ $group['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <nav class="admin-sidebar-subnav" aria-label="{{ $activeGroup['label'] }} section">
            <div class="admin-subnav-head">{{ $activeGroup['label'] }}</div>
            @foreach($activeGroup['items'] as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="{{ AdminNavigation::itemIsActive($item) ? 'active' : '' }}"
                    @if(AdminNavigation::itemIsActive($item)) aria-current="page" @endif
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="admin-sidebar-foot">
        <a href="{{ route('home') }}" target="_blank" rel="noopener">View public site →</a>
    </div>
</aside>
