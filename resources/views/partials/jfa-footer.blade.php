@php
    $footAbout = $siteSetting?->footer_about ?? "India's premium Online Travel Agency. Book flights, hotels, packages and more with guaranteed best prices.";
    $copyName = $siteSetting?->footer_copyright_name ?? 'Jet Fly Airways';
    $supportPhones = ($siteSetting ?? null)?->supportPhoneList() ?? [['label' => 'Support', 'phone' => '+91 1800-000-0000']];
    $supportEmails = ($siteSetting ?? null)?->supportEmailList() ?? [['label' => 'Support', 'email' => 'support@jetflyairways.com']];
    $officeAddresses = ($siteSetting ?? null)?->officeAddressList() ?? [];
    $footerBadges = trim((string) ($siteSetting?->footer_badges ?? ''));
    $logoUrl = ($siteSetting ?? null)?->logo_image
        ? \App\Support\PublicImageStorage::url($siteSetting->logo_image)
        : null;
    $socials = array_filter([
        'public' => ['Facebook', $siteSetting?->social_facebook_url],
        'photo_camera' => ['Instagram', $siteSetting?->social_instagram_url],
        'share' => ['X', $siteSetting?->social_twitter_url],
        'work' => ['LinkedIn', $siteSetting?->social_linkedin_url],
    ], fn ($s) => filled($s[1]));
    $footerMenu = $footerMenu ?? collect();
@endphp

<footer class="jfa-footer">
    <div class="jfa-footer__strip">
        <div class="jfa-container jfa-footer__strip-inner">
            <div class="jfa-footer__strip-copy">
                <h3>Get travel deals in your inbox</h3>
                <p>Exclusive fares, hotel offers and holiday packages — no spam.</p>
            </div>
            <form class="jfa-footer__form" action="{{ route('contact.create') }}" method="get">
                <input type="email" name="email" placeholder="Your email address" aria-label="Email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="jfa-container jfa-footer__body">
        <div class="jfa-footer__columns">
            <div class="jfa-footer__brand">
                <a href="{{ route('home') }}" class="jfa-footer__logo-wrap">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $copyName }}" class="jfa-footer__logo">
                    @else
                        <span class="jfa-footer__logo-fallback"><span class="material-symbols-outlined filled">flight</span></span>
                    @endif
                    <span class="jfa-footer__logo-text">
                        <strong>{{ $copyName }}</strong>
                        <small>{{ $siteSetting?->brand_tagline ?? 'Book · Fly · Stay' }}</small>
                    </span>
                </a>
                <p class="jfa-footer__about">{{ $footAbout }}</p>
                @if(count($socials))
                    <div class="jfa-footer__social">
                        @foreach($socials as $icon => [$label, $url])
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">
                                <span class="material-symbols-outlined">{{ $icon }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @forelse($footerMenu as $col)
                @if($col->children->isNotEmpty())
                    <div class="jfa-footer__col">
                        <h4 class="jfa-footer__title">{{ $col->label }}</h4>
                        <ul class="jfa-footer__links">
                            @foreach($col->children as $link)
                                <li><a href="{{ $link->resolvedUrl() }}" @if($link->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $link->label }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @empty
                <div class="jfa-footer__col">
                    <h4 class="jfa-footer__title">Services</h4>
                    <ul class="jfa-footer__links">
                        <li><a href="{{ route('module.index', 'flights') }}">Flights</a></li>
                        <li><a href="{{ route('module.index', 'hotels') }}">Hotels</a></li>
                        <li><a href="{{ route('module.index', 'packages') }}">Holiday Packages</a></li>
                        <li><a href="{{ route('module.index', 'trains') }}">Trains</a></li>
                        <li><a href="{{ route('module.index', 'buses') }}">Buses</a></li>
                        <li><a href="{{ route('module.index', 'cabs') }}">Cabs</a></li>
                    </ul>
                </div>
                <div class="jfa-footer__col">
                    <h4 class="jfa-footer__title">Support</h4>
                    <ul class="jfa-footer__links">
                        <li><a href="{{ route('faq.index') }}">Help Center</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'refund']) }}">Cancellation &amp; Refunds</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'privacy']) }}">Privacy Policy</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'terms']) }}">Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('contact.create') }}">Contact Us</a></li>
                    </ul>
                </div>
                <div class="jfa-footer__col">
                    <h4 class="jfa-footer__title">Company</h4>
                    <ul class="jfa-footer__links">
                        <li><a href="{{ route('pages.show', ['slug' => 'about']) }}">About Us</a></li>
                        <li><a href="{{ route('jobs.index') }}">Careers</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('refer-earn') }}">Refer &amp; Earn</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'sitemap']) }}">Sitemap</a></li>
                    </ul>
                </div>
            @endforelse
        </div>

        <div class="jfa-footer__contact">
            @if(count($officeAddresses))
                <div class="jfa-footer__contact-item">
                    <div class="jfa-footer__contact-icon-wrap" aria-hidden="true">
                        <span class="material-symbols-outlined filled">location_on</span>
                    </div>
                    <div class="jfa-footer__contact-body">
                        <h4>Our offices</h4>
                        <ul class="jfa-footer__contact-list">
                            @foreach($officeAddresses as $addrRow)
                                <li>
                                    <span class="jfa-footer__contact-tag">{{ $addrRow['label'] }}</span>
                                    <p class="jfa-footer__contact-text">{!! nl2br(e($addrRow['address'])) !!}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(count($supportPhones))
                <div class="jfa-footer__contact-item">
                    <div class="jfa-footer__contact-icon-wrap" aria-hidden="true">
                        <span class="material-symbols-outlined filled">call</span>
                    </div>
                    <div class="jfa-footer__contact-body">
                        <h4>Call us</h4>
                        <ul class="jfa-footer__contact-list">
                            @foreach($supportPhones as $phoneRow)
                                <li>
                                    <span class="jfa-footer__contact-tag">{{ $phoneRow['label'] }}</span>
                                    <a class="jfa-footer__contact-phone" href="tel:{{ preg_replace('/\s+/', '', $phoneRow['phone']) }}">{{ $phoneRow['phone'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <p class="jfa-footer__contact-note">24/7 customer support</p>
                    </div>
                </div>
            @endif

            @if(count($supportEmails))
                <div class="jfa-footer__contact-item">
                    <div class="jfa-footer__contact-icon-wrap" aria-hidden="true">
                        <span class="material-symbols-outlined filled">mail</span>
                    </div>
                    <div class="jfa-footer__contact-body">
                        <h4>Email us</h4>
                        <ul class="jfa-footer__contact-list">
                            @foreach($supportEmails as $mailRow)
                                <li>
                                    <span class="jfa-footer__contact-tag">{{ $mailRow['label'] }}</span>
                                    <a href="mailto:{{ $mailRow['email'] }}">{{ $mailRow['email'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="jfa-footer__meta">
        <div class="jfa-container jfa-footer__meta-inner">
            @if(filled($footerBadges))
                <p class="jfa-footer__trust-line">
                    <span class="material-symbols-outlined filled" aria-hidden="true">verified</span>
                    {{ $footerBadges }}
                </p>
            @else
                <ul class="jfa-footer__trust-list">
                    <li><span class="material-symbols-outlined filled">verified_user</span> Secure payments</li>
                    <li><span class="material-symbols-outlined filled">lock</span> SSL encrypted</li>
                    <li><span class="material-symbols-outlined">support_agent</span> 24/7 support</li>
                </ul>
            @endif
            <div class="jfa-footer__legal">
                <span>&copy; {{ date('Y') }} {{ $copyName }}. All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>
