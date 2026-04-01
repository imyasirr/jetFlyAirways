<footer class="site-footer">
    <div class="footer-main">
        <div class="container footer-grid">
            <div class="footer-col footer-brand">
                <span class="footer-logo">Jet Fly Airways</span>
                <p class="footer-desc">Flights, hotels, holidays, and ground transport — with customer accounts, admin-managed menus, coupons, and live inventory.</p>
                <div class="footer-social" aria-label="Social">
                    <span class="footer-social-label">Follow</span>
                    <span class="footer-social-links">LinkedIn · X · Instagram</span>
                </div>
            </div>
            @php $footerMenu = $footerMenu ?? collect(); @endphp
            @forelse($footerMenu as $col)
                <div class="footer-col">
                    <h3 class="footer-heading">{{ $col->label }}</h3>
                    @if($col->children->isNotEmpty())
                        <ul class="footer-links">
                            @foreach($col->children as $link)
                                <li>
                                    <a href="{{ $link->resolvedUrl() }}" @if($link->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $link->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @empty
                <div class="footer-col">
                    <h3 class="footer-heading">Company</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('pages.show', ['slug' => 'about']) }}">About</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'contact']) }}">Contact</a></li>
                    </ul>
                </div>
            @endforelse
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <span>© {{ date('Y') }} Jet Fly Airways. All rights reserved.</span>
            <span class="footer-badges">GST-ready · PCI-DSS aligned checkout (integration) · ISO 27001 roadmap</span>
        </div>
    </div>
</footer>
