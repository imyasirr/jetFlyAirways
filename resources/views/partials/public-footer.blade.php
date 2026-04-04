@php
    $footAbout = $siteSetting?->footer_about ?? 'Flights, hotels, holidays, and ground transport — with customer accounts, admin-managed menus, coupons, and live inventory.';
    $footBadges = $siteSetting?->footer_badges ?? 'GST-ready · PCI-DSS aligned checkout (integration) · ISO 27001 roadmap';
    $copyName = $siteSetting?->footer_copyright_name ?? 'Jet Fly Airways';
    $socials = array_filter([
        'Facebook' => $siteSetting?->social_facebook_url,
        'Instagram' => $siteSetting?->social_instagram_url,
        'LinkedIn' => $siteSetting?->social_linkedin_url,
        'X' => $siteSetting?->social_twitter_url,
    ]);
@endphp
<footer class="mm-footer">
    <div class="mm-footer-main">
        <div class="container mm-footer-grid">
            <div class="mm-footer-brand">
                <span class="mm-footer-logo">{{ $copyName }}</span>
                <p class="mm-footer-desc">{{ $footAbout }}</p>
                @if(count($socials))
                    <div class="mm-footer-social" aria-label="Social">
                        <span class="mm-footer-social-label">Follow us</span>
                        <span class="mm-footer-social-links">
                            @foreach($socials as $label => $url)
                                <a href="{{ $url }}" rel="noopener noreferrer" target="_blank">{{ $label }}</a>
                            @endforeach
                        </span>
                    </div>
                @endif
            </div>
            @php $footerMenu = $footerMenu ?? collect(); @endphp
            @forelse($footerMenu as $col)
                <div class="mm-footer-col">
                    <h3 class="mm-footer-heading">{{ $col->label }}</h3>
                    @if($col->children->isNotEmpty())
                        <ul class="mm-footer-links">
                            @foreach($col->children as $link)
                                <li>
                                    <a href="{{ $link->resolvedUrl() }}" @if($link->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $link->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @empty
                <div class="mm-footer-col">
                    <h3 class="mm-footer-heading">Company</h3>
                    <ul class="mm-footer-links">
                        <li><a href="{{ route('pages.show', ['slug' => 'about']) }}">About</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'contact']) }}">Contact</a></li>
                    </ul>
                </div>
            @endforelse
        </div>
    </div>
    <div class="mm-footer-bottom">
        <div class="container mm-footer-bottom-inner">
            <span>© {{ date('Y') }} {{ $copyName }}. All rights reserved.</span>
            <span class="mm-footer-badges">{{ $footBadges }}</span>
        </div>
    </div>
</footer>
