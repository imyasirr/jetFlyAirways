<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About us',
                'meta_description' => 'Learn about Jet Fly Airways — our mission, network, and commitment to travellers.',
                'body' => <<<'HTML'
<h1 class="section-title">About Jet Fly Airways</h1>
<p>Jet Fly Airways is a full-stack travel commerce platform built for the Indian market: flights, hotels, holiday packages, and ground transport — with a unified admin console and secure customer accounts.</p>
<p>We focus on transparent pricing, reliable inventory from our own database, and a roadmap toward payments, GST-compliant invoicing, and enterprise integrations.</p>
<h2 class="section-title section-title-spaced">What we offer</h2>
<ul>
<li>Live search across flights, stays, buses, trains, and cabs</li>
<li>Customer accounts with bookings, e-tickets, and promotional codes</li>
<li>Admin tools for operations, menus, coupons, and user support</li>
</ul>
HTML,
            ],
            [
                'slug' => 'careers',
                'title' => 'Careers',
                'meta_description' => 'Careers at Jet Fly Airways — join our product, operations, and customer experience teams.',
                'body' => <<<'HTML'
<h1 class="section-title">Careers</h1>
<p>We’re building a modern travel platform. We hire across engineering, product design, operations, and customer success.</p>
<p>Send your CV and role interest to <a href="mailto:careers@jetflyairways.com">careers@jetflyairways.com</a>. We’ll respond when a suitable opening is available.</p>
HTML,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact us',
                'meta_description' => 'Contact Jet Fly Airways — phone, email, and office hours.',
                'body' => <<<'HTML'
<h1 class="section-title">Contact us</h1>
<p><strong>Phone:</strong> <a href="tel:+9118000000000">+91 1800-000-0000</a> (24×7 travel desk)</p>
<p><strong>Email:</strong> <a href="mailto:support@jetflyairways.com">support@jetflyairways.com</a></p>
<p>For corporate or group bookings, mention your travel dates and passenger count so we can route you to the right desk. See also our <a href="/p/help">Help centre</a>.</p>
HTML,
            ],
            [
                'slug' => 'help',
                'title' => 'Help centre',
                'meta_description' => 'Help centre — booking, payments, changes, and refunds at Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Help centre</h1>
<p><strong>Booking:</strong> Search while signed in so your trips appear under My account. You’ll receive a booking code (e-ticket view) after confirmation.</p>
<p><strong>Payments:</strong> Gateway integration can be enabled next — until then, status may show as pending.</p>
<p><strong>Changes &amp; cancellations:</strong> See our <a href="/p/refund">Refund policy</a> or contact support with your booking code.</p>
HTML,
            ],
            [
                'slug' => 'refund',
                'title' => 'Refund policy',
                'meta_description' => 'Refund and cancellation policy for Jet Fly Airways bookings.',
                'body' => <<<'HTML'
<h1 class="section-title">Refund policy</h1>
<p>Refund eligibility depends on the fare rules of the airline, hotel, or operator. Where refundable fares apply, we process refunds after supplier confirmation.</p>
<p>Service fees, if any, may be non-refundable. Initiate a request via <a href="/p/contact">Contact</a> with your booking code.</p>
HTML,
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms of use',
                'meta_description' => 'Terms of use for the Jet Fly Airways website and services.',
                'body' => <<<'HTML'
<h1 class="section-title">Terms of use</h1>
<p>By using this website you agree to use it lawfully and not to misuse automation, scrape inventory without permission, or attempt unauthorized access to accounts or admin systems.</p>
<p>Fares and availability are subject to change until payment is confirmed. Jet Fly Airways may update these terms; continued use constitutes acceptance.</p>
HTML,
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy policy',
                'meta_description' => 'Privacy policy — how Jet Fly Airways handles personal data.',
                'body' => <<<'HTML'
<h1 class="section-title">Privacy policy</h1>
<p>We collect information you provide at registration and booking (name, email, phone, travel details) to fulfil orders and support your account.</p>
<p>We do not sell your personal data. Technical logs may be retained for security. Payment card data should be handled only via certified gateways when enabled.</p>
HTML,
            ],
            [
                'slug' => 'destination-guide',
                'title' => 'Destination guide',
                'meta_description' => 'Explore destinations, seasons, and travel tips with Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Destination guide</h1>
<p>Plan smarter with region highlights, best seasons to visit, and airport or station access tips. Use our search to compare fares and stays — then refine dates for the lowest combined trip cost.</p>
<div class="cms-feature-grid" role="presentation">
<div class="cms-feature-card"><strong>When to go</strong> Shoulder seasons often balance weather and price — adjust for local festivals and holidays.</div>
<div class="cms-feature-card"><strong>Hubs &amp; access</strong> Check the nearest airport or station and ground options (metro, cab, bus) before you lock dates.</div>
<div class="cms-feature-card"><strong>Stay + fly</strong> Bundling hotels with flights can simplify changes — compare both in one session.</div>
</div>
<p class="cms-callout"><strong>Custom trip</strong> Need a tailored itinerary? <a href="/p/contact">Contact us</a> with rough dates and group size.</p>
HTML,
            ],
            [
                'slug' => 'top-deals',
                'title' => 'Top deals',
                'meta_description' => 'Featured fares and package ideas from Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Top deals</h1>
<p>We surface competitive fares from our inventory and partner content. Deals rotate with seasonality — sign in and book early for the widest choice.</p>
<p>Corporate and group desks can request block fares via <a href="/p/corporate-travel">Corporate travel</a> or <a href="/p/group-booking">Group booking</a>.</p>
<div class="cms-actions">
<a class="btn" href="/flights">Search flights</a>
<a class="btn secondary" href="/packages">Browse packages</a>
</div>
HTML,
            ],
            [
                'slug' => 'student-travel',
                'title' => 'Student travel',
                'meta_description' => 'Tips and services for students travelling with Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Student travel</h1>
<p>Carry valid student ID where fare rules require it. Flexible dates often unlock better prices — try mid-week departures when your schedule allows.</p>
<h2 class="section-title">Before you book</h2>
<ul>
<li>Match name on ticket to ID exactly</li>
<li>Check baggage allowance for your fare type</li>
<li>Keep a soft copy of tickets and ID on your phone</li>
</ul>
<p>Questions about baggage or changes? See our <a href="/p/help">Help centre</a> or reach <a href="/p/contact">support</a> with your booking code.</p>
HTML,
            ],
            [
                'slug' => 'corporate-travel',
                'title' => 'Corporate travel',
                'meta_description' => 'Corporate travel desk — invoicing, policy, and repeat bookings at Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Corporate travel</h1>
<p>We support teams that need predictable processes: named travellers, consolidated reporting, and GST-ready documentation as your programme matures.</p>
<div class="cms-callout"><strong>Get in touch</strong> Email <a href="mailto:support@jetflyairways.com">support@jetflyairways.com</a> with company name, approximate monthly volume, and billing preferences.</div>
HTML,
            ],
            [
                'slug' => 'group-booking',
                'title' => 'Group booking',
                'meta_description' => 'Group fares and coordination for flights, hotels, and ground transport.',
                'body' => <<<'HTML'
<h1 class="section-title">Group booking</h1>
<p>For roughly ten or more passengers, or multi-room hotel blocks, our desk can check allotment-style inventory and fare rules before you commit.</p>
<div class="cms-callout"><strong>Share details</strong> Route, dates, and passenger count via <a href="/p/contact">Contact</a> — we’ll confirm feasibility and next steps.</div>
HTML,
            ],
            [
                'slug' => 'press-release',
                'title' => 'Press releases',
                'meta_description' => 'Official announcements and press releases from Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Press releases</h1>
<p>Media enquiries: <a href="mailto:press@jetflyairways.com">press@jetflyairways.com</a>. For customer support, use <a href="/p/contact">Contact us</a>.</p>
<p>Product updates also appear on our <a href="/blog">blog</a> and <a href="/p/travel-news">Travel news</a> page.</p>
HTML,
            ],
            [
                'slug' => 'travel-news',
                'title' => 'Travel news',
                'meta_description' => 'Travel industry news and tips from Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Travel news</h1>
<p>Short updates on routes, seasonal demand, and traveller-friendly reminders. For deep dives, browse the <a href="/blog">Jet Fly blog</a>.</p>
<p class="cms-callout"><strong>Note</strong> Nothing here is formal policy — always confirm fare rules at checkout.</p>
HTML,
            ],
            [
                'slug' => 'sitemap',
                'title' => 'Sitemap',
                'meta_description' => 'Sitemap — main pages and travel services on Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Sitemap</h1>
<p class="cms-sitemap-lead">Jump to a section or open a service — every link opens in this window.</p>
<div class="cms-sitemap">
<section class="cms-sitemap-col" aria-labelledby="sm-main">
<h2 class="cms-sitemap-title" id="sm-main">Main</h2>
<ul class="cms-sitemap-list">
<li><a href="/">Home</a></li>
<li><a href="/welcome">Discover</a></li>
<li><a href="/p/about">About</a></li>
<li><a href="/p/contact">Contact</a></li>
<li><a href="/login">Sign in</a></li>
<li><a href="/register">Register</a></li>
</ul>
</section>
<section class="cms-sitemap-col" aria-labelledby="sm-travel">
<h2 class="cms-sitemap-title" id="sm-travel">Travel</h2>
<ul class="cms-sitemap-list">
<li><a href="/flights">Flights</a></li>
<li><a href="/hotels">Hotels</a></li>
<li><a href="/packages">Packages</a></li>
<li><a href="/buses">Buses</a></li>
<li><a href="/trains">Trains</a></li>
<li><a href="/cabs">Cabs</a></li>
<li><a href="/visa">Visa</a></li>
<li><a href="/insurance">Insurance</a></li>
</ul>
</section>
<section class="cms-sitemap-col" aria-labelledby="sm-guides">
<h2 class="cms-sitemap-title" id="sm-guides">Guides &amp; deals</h2>
<ul class="cms-sitemap-list">
<li><a href="/p/destination-guide">Destination guide</a></li>
<li><a href="/p/top-deals">Top deals</a></li>
<li><a href="/p/student-travel">Student travel</a></li>
<li><a href="/p/corporate-travel">Corporate travel</a></li>
<li><a href="/p/group-booking">Group booking</a></li>
</ul>
</section>
<section class="cms-sitemap-col" aria-labelledby="sm-news">
<h2 class="cms-sitemap-title" id="sm-news">News</h2>
<ul class="cms-sitemap-list">
<li><a href="/blog">Blog</a></li>
<li><a href="/p/press-release">Press releases</a></li>
<li><a href="/p/travel-news">Travel news</a></li>
</ul>
</section>
<section class="cms-sitemap-col" aria-labelledby="sm-legal">
<h2 class="cms-sitemap-title" id="sm-legal">Legal &amp; support</h2>
<ul class="cms-sitemap-list">
<li><a href="/p/help">Help centre</a></li>
<li><a href="/p/refund">Refund policy</a></li>
<li><a href="/p/terms">Terms</a></li>
<li><a href="/p/privacy">Privacy</a></li>
</ul>
</section>
</div>
HTML,
            ],
        ];

        foreach ($pages as $row) {
            Page::query()->updateOrCreate(
                ['slug' => $row['slug']],
                $row + ['is_active' => true]
            );
        }
    }
}
