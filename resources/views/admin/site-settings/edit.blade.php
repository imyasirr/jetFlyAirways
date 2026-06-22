@extends('layouts.admin')

@section('title', 'Site header & footer')

@section('content')
    <div class="card admin-form-page">
        <div class="admin-settings-intro">
            Controls the <strong>top promo bar</strong>, <strong>header/footer logo</strong>, <strong>support contacts</strong>, <strong>home hero background</strong>, <strong>footer</strong>, and <strong>social links</strong>.
            Navigation links: <a href="{{ route('admin.menu-items.index') }}">Header &amp; footer menu</a>.
            Hero promos: <a href="{{ route('admin.banners.index') }}">Home banners</a>.
        </div>

        <form method="post" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="admin-form-grid admin-form-grid--wide">
            @csrf
            @method('PUT')

            <section class="admin-form-section">
                <div class="admin-form-section__head">
                    <h3>Top promo bar</h3>
                    <p>Short message shown in the header strip on every public page.</p>
                </div>
                <div class="admin-form-section__body">
                    <label class="admin-field-full">Top bar message (left)
                        <textarea name="topstrip_left" rows="3" placeholder="Promo or announcement">{{ old('topstrip_left', $setting->topstrip_left) }}</textarea>
                    </label>
                </div>
            </section>

            @php
                $phoneRows = old('support_phones', $setting->supportPhoneList());
                if (! is_array($phoneRows) || $phoneRows === []) {
                    $phoneRows = [['label' => 'Support', 'phone' => '']];
                }
                $emailRows = old('support_emails', $setting->supportEmailList());
                if (! is_array($emailRows) || $emailRows === []) {
                    $emailRows = [['label' => 'Support', 'email' => '']];
                }
                $addressRows = old('office_addresses', $setting->officeAddressList());
                if (! is_array($addressRows) || $addressRows === []) {
                    $addressRows = [['label' => 'Registered Office', 'address' => '']];
                }
            @endphp

            <section class="admin-form-section">
                <div class="admin-form-section__head">
                    <h3>Contact details</h3>
                    <p>Shown in the footer. The first phone and email are also used in the header.</p>
                </div>
                <div class="admin-form-section__body">
                    <div class="admin-repeater-block admin-field-full">
                        <strong style="font-size:13px;color:#334155;">Phone numbers</strong>
                        <div id="support-phones-list" class="admin-repeater-list">
                            @foreach($phoneRows as $index => $row)
                                <div class="admin-repeater-row admin-repeater-row--inline" data-repeater-row>
                                    <label>Label
                                        <input type="text" name="support_phones[{{ $index }}][label]" value="{{ $row['label'] ?? 'Support' }}" placeholder="Support">
                                    </label>
                                    <label>Phone
                                        <input type="text" name="support_phones[{{ $index }}][phone]" value="{{ $row['phone'] ?? '' }}" placeholder="+91 …">
                                    </label>
                                    <button type="button" class="admin-icon-btn admin-icon-btn--danger admin-repeater-remove" data-repeater-remove aria-label="Remove phone">
                                        <span class="material-symbols-outlined" aria-hidden="true">delete</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn ghost admin-repeater-add" data-repeater-add="support-phones-list" data-repeater-prefix="support_phones" data-repeater-type="phone">
                            <span class="material-symbols-outlined" aria-hidden="true">add</span> Add phone
                        </button>
                    </div>

                    <div class="admin-repeater-block admin-field-full">
                        <strong style="font-size:13px;color:#334155;">Email addresses</strong>
                        <div id="support-emails-list" class="admin-repeater-list">
                            @foreach($emailRows as $index => $row)
                                <div class="admin-repeater-row admin-repeater-row--inline" data-repeater-row>
                                    <label>Label
                                        <input type="text" name="support_emails[{{ $index }}][label]" value="{{ $row['label'] ?? 'Support' }}" placeholder="Support">
                                    </label>
                                    <label>Email
                                        <input type="email" name="support_emails[{{ $index }}][email]" value="{{ $row['email'] ?? '' }}" placeholder="support@example.com">
                                    </label>
                                    <button type="button" class="admin-icon-btn admin-icon-btn--danger admin-repeater-remove" data-repeater-remove aria-label="Remove email">
                                        <span class="material-symbols-outlined" aria-hidden="true">delete</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn ghost admin-repeater-add" data-repeater-add="support-emails-list" data-repeater-prefix="support_emails" data-repeater-type="email">
                            <span class="material-symbols-outlined" aria-hidden="true">add</span> Add email
                        </button>
                    </div>

                    <div class="admin-repeater-block admin-field-full">
                        <strong style="font-size:13px;color:#334155;">Office addresses</strong>
                        <div id="office-addresses-list" class="admin-repeater-list">
                            @foreach($addressRows as $index => $row)
                                <div class="admin-repeater-row admin-repeater-row--stack" data-repeater-row>
                                    <button type="button" class="admin-icon-btn admin-icon-btn--danger admin-repeater-remove" data-repeater-remove aria-label="Remove address">
                                        <span class="material-symbols-outlined" aria-hidden="true">delete</span>
                                    </button>
                                    <label>Label
                                        <input type="text" name="office_addresses[{{ $index }}][label]" value="{{ $row['label'] ?? 'Registered Office' }}" placeholder="Registered Office">
                                    </label>
                                    <label>Address
                                        <textarea name="office_addresses[{{ $index }}][address]" rows="3" placeholder="Street, city, state, PIN">{{ $row['address'] ?? '' }}</textarea>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn ghost admin-repeater-add" data-repeater-add="office-addresses-list" data-repeater-prefix="office_addresses" data-repeater-type="address">
                            <span class="material-symbols-outlined" aria-hidden="true">add</span> Add address
                        </button>
                    </div>
                </div>
            </section>

            <section class="admin-form-section">
                <div class="admin-form-section__head">
                    <h3>Brand &amp; logo</h3>
                    <p>Text and image used in the public header and footer.</p>
                </div>
                <div class="admin-form-section__body">
                    <label>Brand name (line 1)
                        <input type="text" name="brand_name" value="{{ old('brand_name', $setting->brand_name) }}">
                    </label>
                    <label>Brand tagline (line 2)
                        <input type="text" name="brand_tagline" value="{{ old('brand_tagline', $setting->brand_tagline) }}">
                    </label>
                    <div class="admin-field-full">
                        @include('admin.partials.image-upload', [
                            'label' => 'Header & footer logo',
                            'name' => 'logo_image_file',
                            'currentPath' => $setting->logo_image,
                            'required' => false,
                            'hint' => 'Optional PNG/WebP with transparent background. Max 4 MB.',
                        ])
                    </div>
                    <label class="admin-field-full admin-checkbox-row">
                        <input type="checkbox" name="clear_logo_image" value="1" @checked(old('clear_logo_image'))>
                        <span>Remove logo image (use brand text only)</span>
                    </label>
                </div>
            </section>

            <section class="admin-form-section">
                <div class="admin-form-section__head">
                    <h3>Home hero</h3>
                    <p>Background image behind the search panel on the homepage.</p>
                </div>
                <div class="admin-form-section__body">
                    <div class="admin-field-full">
                        @include('admin.partials.image-upload', [
                            'label' => 'Hero background image',
                            'name' => 'hero_image_file',
                            'currentPath' => $setting->hero_image,
                            'required' => false,
                            'hint' => 'Optional full-width photo for / and /welcome. Max 10 MB.',
                        ])
                    </div>
                    <label class="admin-field-full admin-checkbox-row">
                        <input type="checkbox" name="clear_hero_image" value="1" @checked(old('clear_hero_image'))>
                        <span>Remove hero image (gradient only)</span>
                    </label>
                </div>
            </section>

            <section class="admin-form-section">
                <div class="admin-form-section__head">
                    <h3>Footer content</h3>
                    <p>About text and trust line shown above the footer links.</p>
                </div>
                <div class="admin-form-section__body">
                    <label class="admin-field-full">About text
                        <textarea name="footer_about" rows="4">{{ old('footer_about', $setting->footer_about) }}</textarea>
                    </label>
                    <label>Trust / payment line
                        <input type="text" name="footer_badges" value="{{ old('footer_badges', $setting->footer_badges) }}">
                    </label>
                    <label>Copyright name
                        <input type="text" name="footer_copyright_name" value="{{ old('footer_copyright_name', $setting->footer_copyright_name) }}" placeholder="Jet Fly Airways">
                    </label>
                </div>
            </section>

            <section class="admin-form-section">
                <div class="admin-form-section__head">
                    <h3>Social &amp; chat</h3>
                    <p>Optional profile URLs, Tawk.to live chat widget, and fallback chat link.</p>
                </div>
                <div class="admin-form-section__body">
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
                    <label class="admin-field-full">Tawk.to Property ID
                        <input type="text" name="tawk_property_id" value="{{ old('tawk_property_id', $setting->tawk_property_id) }}" placeholder="6a3905d824f3ef1d47878c79" maxlength="64">
                    </label>
                    <label class="admin-field-full">Tawk.to Widget ID
                        <input type="text" name="tawk_widget_id" value="{{ old('tawk_widget_id', $setting->tawk_widget_id) }}" placeholder="1jrnbtmeb" maxlength="64">
                    </label>
                    <label class="admin-field-full">Or paste Tawk embed URL
                        <input type="text" name="tawk_embed_url" value="{{ old('tawk_embed_url') }}" placeholder="https://embed.tawk.to/PROPERTY_ID/WIDGET_ID">
                    </label>
                    <p class="admin-field-full" style="margin:-6px 0 0;font-size:12px;color:#64748b;line-height:1.5;">From your Tawk.to dashboard: Administration → Chat Widget → copy the embed link. When set, the chat widget loads on every public page and opens from the floating chat button.</p>
                    <label class="admin-field-full">Fallback live chat URL (optional)
                        <input type="url" name="live_chat_url" value="{{ old('live_chat_url', $setting->live_chat_url) }}" placeholder="https://tawk.to/…">
                    </label>
                    <p class="admin-field-full" style="margin:-6px 0 0;font-size:12px;color:#64748b;">Used only if Tawk.to IDs above are empty — opens in a new tab.</p>
                </div>
            </section>

            <div class="admin-field-full form-actions">
                <button type="submit" class="btn">Save settings</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    function reindexList(list, prefix, type) {
        list.querySelectorAll('[data-repeater-row]').forEach(function (row, index) {
            row.querySelectorAll('input, textarea').forEach(function (field) {
                if (field.name.includes('[label]')) {
                    field.name = prefix + '[' + index + '][label]';
                } else if (type === 'phone') {
                    field.name = prefix + '[' + index + '][phone]';
                } else if (type === 'email') {
                    field.name = prefix + '[' + index + '][email]';
                } else {
                    field.name = prefix + '[' + index + '][address]';
                }
            });
        });

        var count = list.querySelectorAll('[data-repeater-row]').length;
        list.querySelectorAll('[data-repeater-remove]').forEach(function (btn) {
            btn.disabled = count <= 1;
        });
    }

    function removeBtnHtml() {
        return '<button type="button" class="admin-icon-btn admin-icon-btn--danger admin-repeater-remove" data-repeater-remove aria-label="Remove">' +
            '<span class="material-symbols-outlined" aria-hidden="true">delete</span></button>';
    }

    function buildRow(prefix, type, index) {
        var row = document.createElement('div');
        if (type === 'address') {
            row.className = 'admin-repeater-row admin-repeater-row--stack';
            row.setAttribute('data-repeater-row', '');
            row.innerHTML = removeBtnHtml() +
                '<label>Label' +
                    '<input type="text" name="' + prefix + '[' + index + '][label]" value="Registered Office" placeholder="Registered Office">' +
                '</label>' +
                '<label>Address' +
                    '<textarea name="' + prefix + '[' + index + '][address]" rows="3" placeholder="Street, city, state, PIN"></textarea>' +
                '</label>';
            return row;
        }

        row.className = 'admin-repeater-row admin-repeater-row--inline';
        row.setAttribute('data-repeater-row', '');
        var valueKey = type === 'phone' ? 'phone' : 'email';
        var inputType = type === 'phone' ? 'text' : 'email';
        var placeholder = type === 'phone' ? '+91 …' : 'support@example.com';
        var valueLabel = type === 'phone' ? 'Phone' : 'Email';
        row.innerHTML =
            '<label>Label' +
                '<input type="text" name="' + prefix + '[' + index + '][label]" value="Support" placeholder="Support">' +
            '</label>' +
            '<label>' + valueLabel +
                '<input type="' + inputType + '" name="' + prefix + '[' + index + '][' + valueKey + ']" value="" placeholder="' + placeholder + '">' +
            '</label>' +
            removeBtnHtml();
        return row;
    }

    document.querySelectorAll('[data-repeater-add]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var list = document.getElementById(btn.getAttribute('data-repeater-add'));
            if (!list) return;
            var prefix = btn.getAttribute('data-repeater-prefix');
            var type = btn.getAttribute('data-repeater-type');
            var index = list.querySelectorAll('[data-repeater-row]').length;
            list.appendChild(buildRow(prefix, type, index));
            reindexList(list, prefix, type);
        });
    });

    document.querySelectorAll('.admin-repeater-list').forEach(function (list) {
        var prefix = list.id === 'support-phones-list' ? 'support_phones'
            : list.id === 'support-emails-list' ? 'support_emails'
            : 'office_addresses';
        var type = list.id === 'support-phones-list' ? 'phone'
            : list.id === 'support-emails-list' ? 'email'
            : 'address';

        reindexList(list, prefix, type);

        list.addEventListener('click', function (event) {
            var btn = event.target.closest('[data-repeater-remove]');
            if (!btn || btn.disabled) return;
            var row = btn.closest('[data-repeater-row]');
            if (!row || list.querySelectorAll('[data-repeater-row]').length <= 1) return;
            row.remove();
            reindexList(list, prefix, type);
        });
    });
})();
</script>
@endpush
