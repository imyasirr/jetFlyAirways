@extends('layouts.admin')

@section('title', 'Site header & footer')

@section('content')
    <div class="card">
        <h2 class="section-title" style="font-size:1.1rem;">Public site chrome (OTA-style)</h2>
        <p style="font-size:14px;color:#64748b;margin:0 0 16px;max-width:72ch;line-height:1.55;">
            Controls the <strong>top promo bar</strong>, <strong>logo text</strong>, <strong>support phone/email</strong>, <strong>home hero background</strong> (full-width image behind search — like MMT), <strong>footer</strong>, and <strong>social links</strong>.
            Main navigation links are still under <a href="{{ route('admin.menu-items.index') }}">Header &amp; footer menu</a>.
            Promo slides under the hero come from <a href="{{ route('admin.banners.index') }}">Home banners</a>.
        </p>
        <form method="post" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="admin-form-grid">
            @csrf
            @method('PUT')
            <label class="admin-field-full">Top bar message (left)
                <textarea name="topstrip_left" rows="2" placeholder="Promo or announcement">{{ old('topstrip_left', $setting->topstrip_left) }}</textarea>
            </label>
            <label>Support phone
                <input type="text" name="support_phone" value="{{ old('support_phone', $setting->support_phone) }}" placeholder="+91 …">
            </label>
            <label>Support email
                <input type="email" name="support_email" value="{{ old('support_email', $setting->support_email) }}">
            </label>
            <label>Brand name (logo, line 1)
                <input type="text" name="brand_name" value="{{ old('brand_name', $setting->brand_name) }}">
            </label>
            <label>Brand tagline (logo, line 2)
                <input type="text" name="brand_tagline" value="{{ old('brand_tagline', $setting->brand_tagline) }}">
            </label>
            <div class="admin-field-full">
                @include('admin.partials.image-upload', [
                    'label' => 'Home hero background image',
                    'name' => 'hero_image_file',
                    'currentPath' => $setting->hero_image,
                    'required' => false,
                    'hint' => 'Optional. Full-width photo behind the headline + search card on / and /welcome. JPEG, PNG, WebP or GIF — max 10 MB.',
                ])
            </div>
            <label class="admin-field-full" style="flex-direction:row;align-items:center;gap:10px;">
                <input type="checkbox" name="clear_hero_image" value="1" @checked(old('clear_hero_image'))>
                <span>Remove hero image (use gradient only)</span>
            </label>
            <label class="admin-field-full">Footer about text
                <textarea name="footer_about" rows="4">{{ old('footer_about', $setting->footer_about) }}</textarea>
            </label>
            <label>Footer trust / payment line
                <input type="text" name="footer_badges" value="{{ old('footer_badges', $setting->footer_badges) }}">
            </label>
            <label>Copyright name
                <input type="text" name="footer_copyright_name" value="{{ old('footer_copyright_name', $setting->footer_copyright_name) }}" placeholder="Jet Fly Airways">
            </label>
            <p class="admin-field-full" style="margin:8px 0 0;font-weight:800;font-size:13px;color:#334155;">Social profile URLs (optional)</p>
            <label>Facebook
                <input type="url" name="social_facebook_url" value="{{ old('social_facebook_url', $setting->social_facebook_url) }}" placeholder="https://…">
            </label>
            <label>Instagram
                <input type="url" name="social_instagram_url" value="{{ old('social_instagram_url', $setting->social_instagram_url) }}" placeholder="https://…">
            </label>
            <label>LinkedIn
                <input type="url" name="social_linkedin_url" value="{{ old('social_linkedin_url', $setting->social_linkedin_url) }}" placeholder="https://…">
            </label>
            <label>X (Twitter)
                <input type="url" name="social_twitter_url" value="{{ old('social_twitter_url', $setting->social_twitter_url) }}" placeholder="https://…">
            </label>
            <label class="admin-field-full">Live chat URL (optional)
                <input type="url" name="live_chat_url" value="{{ old('live_chat_url', $setting->live_chat_url) }}" placeholder="https://tawk.to/... or custom support URL">
            </label>
            <div class="admin-field-full" style="margin-top:4px;">
                <button type="submit" class="btn">Save</button>
            </div>
        </form>
    </div>
@endsection
