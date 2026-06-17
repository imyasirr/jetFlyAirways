@php
    $footAbout = $siteSetting?->footer_about ?? 'Elevating the standards of global travel. Committed to luxury, safety, and your destination.';
    $footBadges = $siteSetting?->footer_badges ?? 'GST-ready - PCI-DSS aligned checkout - Secure booking flow';
    $copyName = $siteSetting?->footer_copyright_name ?? 'Jet Fly Airways';
    $phone = $siteSetting?->support_phone ?? '+91 1800-000-0000';
    $email = $siteSetting?->support_email ?? 'support@jetflyairways.com';
    $logoUrl = ($siteSetting ?? null)?->logo_image
        ? \App\Support\PublicImageStorage::url($siteSetting->logo_image)
        : null;
    $socials = array_filter([
        'public' => ['Facebook', $siteSetting?->social_facebook_url],
        'photo_camera' => ['Instagram', $siteSetting?->social_instagram_url],
        'work' => ['LinkedIn', $siteSetting?->social_linkedin_url],
        'share' => ['X', $siteSetting?->social_twitter_url],
    ], fn ($s) => filled($s[1]));
@endphp
<footer class="mm-footer">
    <div class="container mm-footer-grid">
        <div class="mm-footer-brand">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $copyName }}" class="mm-footer-logo-img">
            @endif
            <span class="mm-footer-logo">{{ $copyName }}</span>
            <p class="mm-footer-desc">{{ $footAbout }}</p>
            <div class="mm-footer-contact">
                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                <a href="mailto:{{ $email }}">{{ $email }}</a>
            </div>
            @if(count($socials))
                <div class="mm-footer-social" aria-label="Social">
                    @foreach($socials as $icon => [$label, $url])
                        <a href="{{ $url }}" class="mm-footer-social-circle" rel="noopener noreferrer" target="_blank" aria-label="{{ $label }}" title="{{ $label }}">
                            <span class="material-symbols-outlined" aria-hidden="true">{{ $icon }}</span>
                        </a>
                    @endforeach
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
                <h3 class="mm-footer-heading">Quick Links</h3>
                <ul class="mm-footer-links">
                    <li><a href="{{ route('pages.show', ['slug' => 'about']) }}">About Us</a></li>
                    <li><a href="{{ route('jobs.index') }}">Careers</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('module.index', 'flights') }}">Flights</a></li>
                    <li><a href="{{ route('module.index', 'hotels') }}">Hotels</a></li>
                </ul>
            </div>
            <div class="mm-footer-col">
                <h3 class="mm-footer-heading">Support</h3>
                <ul class="mm-footer-links">
                    <li><a href="{{ route('faq.index') }}">Help Center</a></li>
                    <li><a href="{{ route('contact.create') }}">Contact Us</a></li>
                    <li><a href="{{ route('pages.show', ['slug' => 'privacy']) }}">Privacy Policy</a></li>
                    <li><a href="{{ route('pages.show', ['slug' => 'terms']) }}">Terms of Service</a></li>
                    <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                </ul>
            </div>
        @endforelse

        <div class="mm-footer-newsletter">
            <h3 class="mm-footer-heading">Newsletter</h3>
            <p>Get the best flight deals and seasonal offers right in your inbox.</p>
            <form class="mm-footer-newsletter-form" action="{{ route('contact.create') }}" method="get">
                <input type="email" name="email" placeholder="Your email" aria-label="Email address">
                <button type="submit">Join</button>
            </form>
        </div>

        <div class="mm-footer-bottom-inner">
            <span>© {{ date('Y') }} {{ $copyName }}. All rights reserved.</span>
            <span class="mm-footer-badges">{{ $footBadges }}</span>
        </div>
    </div>
</footer>
