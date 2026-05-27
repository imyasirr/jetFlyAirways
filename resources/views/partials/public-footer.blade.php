@php
    $footAbout = $siteSetting?->footer_about ?? 'Flights, hotels, holidays and ground transport with curated deals, secure checkout and expert travel support.';
    $footBadges = $siteSetting?->footer_badges ?? 'GST-ready - PCI-DSS aligned checkout - Secure booking flow';
    $copyName = $siteSetting?->footer_copyright_name ?? 'Jet Fly Airways';
    $phone = $siteSetting?->support_phone ?? '+91 1800-000-0000';
    $email = $siteSetting?->support_email ?? 'support@jetflyairways.com';
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
                <div class="mm-footer-contact">
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                </div>
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
                        <li><a href="{{ route('pages.show', ['slug' => 'about']) }}">About us</a></li>
                        <li><a href="{{ route('jobs.index') }}">Careers</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('contact.create') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="mm-footer-col">
                    <h3 class="mm-footer-heading">Services</h3>
                    <ul class="mm-footer-links">
                        <li><a href="{{ route('module.index', 'flights') }}">Flights</a></li>
                        <li><a href="{{ route('module.index', 'hotels') }}">Hotels</a></li>
                        <li><a href="{{ route('module.index', 'packages') }}">Holidays</a></li>
                        <li><a href="{{ route('module.index', 'cabs') }}">Cabs</a></li>
                    </ul>
                </div>
                <div class="mm-footer-col">
                    <h3 class="mm-footer-heading">Support</h3>
                    <ul class="mm-footer-links">
                        <li><a href="{{ route('faq.index') }}">FAQs</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'refund']) }}">Refund policy</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'terms']) }}">Terms</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'privacy']) }}">Privacy</a></li>
                    </ul>
                </div>
            @endforelse

            <div class="mm-footer-newsletter">
                <h3 class="mm-footer-heading">Newsletter</h3>
                <p>Get latest travel deals, seasonal fares and holiday offers.</p>
                <form class="mm-footer-newsletter-form" action="{{ route('contact.create') }}" method="get">
                    <input type="email" name="email" placeholder="Enter your email" aria-label="Email address">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
    <div class="mm-footer-bottom">
        <div class="container mm-footer-bottom-inner">
            <span>Copyright {{ date('Y') }} {{ $copyName }}. All rights reserved.</span>
            <span class="mm-footer-badges">{{ $footBadges }}</span>
        </div>
    </div>
</footer>
