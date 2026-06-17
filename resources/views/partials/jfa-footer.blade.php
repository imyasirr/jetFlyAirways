@php
    $footAbout = $siteSetting?->footer_about ?? "India's premium Online Travel Agency. Book flights, hotels, packages and more with guaranteed best prices.";
    $copyName = $siteSetting?->footer_copyright_name ?? 'Jet Fly Airways';
    $phone = $siteSetting?->support_phone ?? '+91 1800-000-0000';
    $email = $siteSetting?->support_email ?? 'support@jetflyairways.com';
    $logoUrl = ($siteSetting ?? null)?->logo_image
        ? \App\Support\PublicImageStorage::url($siteSetting->logo_image)
        : null;
    $socials = array_filter([
        'public' => ['Facebook', $siteSetting?->social_facebook_url],
        'photo_camera' => ['Instagram', $siteSetting?->social_instagram_url],
        'share' => ['X', $siteSetting?->social_twitter_url],
        'work' => ['LinkedIn', $siteSetting?->social_linkedin_url],
    ], fn ($s) => filled($s[1]));
@endphp

<footer class="jfa-footer">
    <div class="jfa-footer__newsletter">
        <div class="jfa-container jfa-footer__newsletter-inner">
            <div>
                <h3>Get Exclusive Travel Deals</h3>
                <p>Join travellers getting our best deals every week.</p>
            </div>
            <form class="jfa-footer__form" action="{{ route('contact.create') }}" method="get">
                <input type="email" name="email" placeholder="your@email.com" aria-label="Email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="jfa-container jfa-footer__grid">
        <div class="jfa-footer__brand">
            <a href="{{ route('home') }}" class="jfa-brand" style="margin-bottom:12px;">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $copyName }}" class="jfa-brand__logo">
                @else
                    <span class="jfa-brand__icon"><span class="material-symbols-outlined filled">flight</span></span>
                @endif
                <span>
                    <span class="jfa-brand__name">{{ $copyName }}</span>
                    <span class="jfa-brand__tag">{{ $siteSetting?->brand_tagline ?? 'Book · Fly · Stay' }}</span>
                </span>
            </a>
            <p class="jfa-footer__about">{{ $footAbout }}</p>
            <div class="jfa-footer__contact">
                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><span class="material-symbols-outlined" style="font-size:18px;">call</span> {{ $phone }}</a>
                <a href="mailto:{{ $email }}"><span class="material-symbols-outlined" style="font-size:18px;">mail</span> {{ $email }}</a>
                <span style="display:flex;align-items:center;gap:8px;font-size:14px;color:rgba(191,219,254,.9);"><span class="material-symbols-outlined" style="font-size:18px;">schedule</span> 24/7 Customer Support</span>
            </div>
            @if(count($socials))
                <div class="jfa-footer__social">
                    @foreach($socials as $icon => [$label, $url])
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">
                            <span class="material-symbols-outlined" style="font-size:18px;color:#fff;">{{ $icon }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @php $footerMenu = $footerMenu ?? collect(); @endphp
        @forelse($footerMenu as $col)
            @if($col->children->isNotEmpty())
                <div>
                    <h4 class="jfa-footer__heading">{{ $col->label }}</h4>
                    <ul class="jfa-footer__links">
                        @foreach($col->children as $link)
                            <li><a href="{{ $link->resolvedUrl() }}" @if($link->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $link->label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @empty
            <div>
                <h4 class="jfa-footer__heading">Quick Links</h4>
                <ul class="jfa-footer__links">
                    <li><a href="{{ route('module.index', 'flights') }}">Flights</a></li>
                    <li><a href="{{ route('module.index', 'hotels') }}">Hotels</a></li>
                    <li><a href="{{ route('module.index', 'packages') }}">Holiday Packages</a></li>
                    <li><a href="{{ route('module.index', 'trains') }}">Trains</a></li>
                    <li><a href="{{ route('module.index', 'buses') }}">Buses</a></li>
                    <li><a href="{{ route('module.index', 'cabs') }}">Cabs</a></li>
                    <li><a href="{{ route('module.index', 'visa') }}">Visa Services</a></li>
                    <li><a href="{{ route('module.index', 'insurance') }}">Travel Insurance</a></li>
                </ul>
            </div>
            <div>
                <h4 class="jfa-footer__heading">Support</h4>
                <ul class="jfa-footer__links">
                    <li><a href="{{ route('faq.index') }}">Help Center</a></li>
                    <li><a href="{{ route('pages.show', ['slug' => 'refund']) }}">Cancellation &amp; Refunds</a></li>
                    <li><a href="{{ route('pages.show', ['slug' => 'privacy']) }}">Privacy Policy</a></li>
                    <li><a href="{{ route('pages.show', ['slug' => 'terms']) }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('pages.show', ['slug' => 'sitemap']) }}">Sitemap</a></li>
                </ul>
            </div>
            <div>
                <h4 class="jfa-footer__heading">Company</h4>
                <ul class="jfa-footer__links">
                    <li><a href="{{ route('pages.show', ['slug' => 'about']) }}">About Us</a></li>
                    <li><a href="{{ route('jobs.index') }}">Careers</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('contact.create') }}">Contact Us</a></li>
                    <li><a href="{{ route('refer-earn') }}">Refer &amp; Earn</a></li>
                </ul>
                <div class="jfa-footer__trust">
                    <div class="jfa-footer__trust-item"><span class="material-symbols-outlined filled">verified_user</span> PCI-DSS Compliant</div>
                    <div class="jfa-footer__trust-item"><span class="material-symbols-outlined filled">lock</span> 256-bit SSL Encrypted</div>
                    <div class="jfa-footer__trust-item"><span class="material-symbols-outlined">receipt_long</span> GST Ready</div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="jfa-container jfa-footer__bottom">
        <span>&copy; {{ date('Y') }} {{ $copyName }}. All rights reserved.</span>
        <span>Made with <span class="material-symbols-outlined filled" style="font-size:14px;color:var(--jfa-auth-orange);vertical-align:middle;">favorite</span> in India</span>
    </div>
</footer>
