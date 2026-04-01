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
<p style="color:var(--muted);line-height:1.7;">Jet Fly Airways is a full-stack travel commerce platform built for the Indian market: flights, hotels, holiday packages, and ground transport — with a unified admin console and secure customer accounts.</p>
<p style="color:var(--muted);line-height:1.7;">We focus on transparent pricing, reliable inventory from our own database, and a roadmap toward payments, GST-compliant invoicing, and enterprise integrations.</p>
<h2 class="section-title" style="margin-top:28px;font-size:1.15rem;">What we offer</h2>
<ul style="color:var(--muted);line-height:1.8;">
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
<p style="color:var(--muted);line-height:1.7;">We’re building a modern travel platform. We hire across engineering, product design, operations, and customer success.</p>
<p style="color:var(--muted);line-height:1.7;">Send your CV and role interest to <a href="mailto:careers@jetflyairways.com" style="color:var(--primary);font-weight:700;">careers@jetflyairways.com</a>. We’ll respond when a suitable opening is available.</p>
HTML,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact us',
                'meta_description' => 'Contact Jet Fly Airways — phone, email, and office hours.',
                'body' => <<<'HTML'
<h1 class="section-title">Contact us</h1>
<p style="margin:0 0 16px;color:var(--muted);"><strong>Phone:</strong> <a href="tel:+9118000000000">+91 1800-000-0000</a> (24×7 travel desk)</p>
<p style="margin:0 0 16px;color:var(--muted);"><strong>Email:</strong> <a href="mailto:support@jetflyairways.com">support@jetflyairways.com</a></p>
<p style="margin:0;color:var(--muted);line-height:1.7;">For corporate or group bookings, mention your travel dates and passenger count so we can route you to the right desk. See also our <a href="/p/help" style="color:var(--primary);font-weight:700;">Help centre</a>.</p>
HTML,
            ],
            [
                'slug' => 'help',
                'title' => 'Help centre',
                'meta_description' => 'Help centre — booking, payments, changes, and refunds at Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Help centre</h1>
<p style="color:var(--muted);line-height:1.7;"><strong>Booking:</strong> Search while signed in so your trips appear under My account. You’ll receive a booking code (e-ticket view) after confirmation.</p>
<p style="color:var(--muted);line-height:1.7;"><strong>Payments:</strong> Gateway integration can be enabled next — until then, status may show as pending.</p>
<p style="color:var(--muted);line-height:1.7;"><strong>Changes &amp; cancellations:</strong> See our <a href="/p/refund" style="color:var(--primary);font-weight:700;">Refund policy</a> or contact support with your booking code.</p>
HTML,
            ],
            [
                'slug' => 'refund',
                'title' => 'Refund policy',
                'meta_description' => 'Refund and cancellation policy for Jet Fly Airways bookings.',
                'body' => <<<'HTML'
<h1 class="section-title">Refund policy</h1>
<p style="color:var(--muted);line-height:1.7;">Refund eligibility depends on the fare rules of the airline, hotel, or operator. Where refundable fares apply, we process refunds after supplier confirmation.</p>
<p style="color:var(--muted);line-height:1.7;">Service fees, if any, may be non-refundable. Initiate a request via <a href="/p/contact" style="color:var(--primary);font-weight:700;">Contact</a> with your booking code.</p>
HTML,
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms of use',
                'meta_description' => 'Terms of use for the Jet Fly Airways website and services.',
                'body' => <<<'HTML'
<h1 class="section-title">Terms of use</h1>
<p style="color:var(--muted);line-height:1.7;">By using this website you agree to use it lawfully and not to misuse automation, scrape inventory without permission, or attempt unauthorized access to accounts or admin systems.</p>
<p style="color:var(--muted);line-height:1.7;">Fares and availability are subject to change until payment is confirmed. Jet Fly Airways may update these terms; continued use constitutes acceptance.</p>
HTML,
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy policy',
                'meta_description' => 'Privacy policy — how Jet Fly Airways handles personal data.',
                'body' => <<<'HTML'
<h1 class="section-title">Privacy policy</h1>
<p style="color:var(--muted);line-height:1.7;">We collect information you provide at registration and booking (name, email, phone, travel details) to fulfil orders and support your account.</p>
<p style="color:var(--muted);line-height:1.7;">We do not sell your personal data. Technical logs may be retained for security. Payment card data should be handled only via certified gateways when enabled.</p>
HTML,
            ],
            [
                'slug' => 'sitemap',
                'title' => 'Sitemap',
                'meta_description' => 'Sitemap — main pages and travel services on Jet Fly Airways.',
                'body' => <<<'HTML'
<h1 class="section-title">Sitemap</h1>
<h2 class="section-title" style="font-size:1.05rem;margin-top:20px;">Main</h2>
<ul style="line-height:2;color:var(--primary);font-weight:600;">
<li><a href="/">Home</a></li>
<li><a href="/welcome">Discover</a></li>
<li><a href="/p/about">About</a></li>
<li><a href="/p/contact">Contact</a></li>
<li><a href="/login">Sign in</a> · <a href="/register">Register</a></li>
</ul>
<h2 class="section-title" style="font-size:1.05rem;margin-top:24px;">Travel</h2>
<ul style="line-height:2;color:var(--primary);font-weight:600;">
<li><a href="/flights">Flights</a></li>
<li><a href="/hotels">Hotels</a></li>
<li><a href="/packages">Packages</a></li>
<li><a href="/buses">Buses</a></li>
<li><a href="/trains">Trains</a></li>
<li><a href="/cabs">Cabs</a></li>
<li><a href="/visa">Visa</a></li>
<li><a href="/insurance">Insurance</a></li>
</ul>
<h2 class="section-title" style="font-size:1.05rem;margin-top:24px;">Legal &amp; support</h2>
<ul style="line-height:2;color:var(--primary);font-weight:600;">
<li><a href="/p/help">Help centre</a></li>
<li><a href="/p/refund">Refund policy</a></li>
<li><a href="/p/terms">Terms</a></li>
<li><a href="/p/privacy">Privacy</a></li>
</ul>
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
