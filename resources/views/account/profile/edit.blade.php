@extends('layouts.account')

@section('title', 'Profile')
@section('heading', 'Profile settings')

@section('content')
    @php
        $avatarUrl = $user->avatarUrl();
        $initials = $user->initials();
        $genderOptions = [
            '' => 'Select gender',
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
            'prefer_not_to_say' => 'Prefer not to say',
        ];
    @endphp

    <div class="jfa-profile-page">
        @if(session('status'))
            <div class="jfa-form-success jfa-profile-alert" role="status">
                <span class="material-symbols-outlined filled">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        <div class="jfa-profile-hero">
            <div class="jfa-profile-hero__avatar" id="jfa-profile-hero-avatar" aria-hidden="true">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="">
                @else
                    <span>{{ $initials }}</span>
                @endif
            </div>
            <div class="jfa-profile-hero__body">
                <h2 class="jfa-profile-hero__name">{{ $user->name }}</h2>
                <p class="jfa-profile-hero__email">{{ $user->email }}</p>
                <div class="jfa-profile-hero__badges">
                    @if($user->email_verified_at)
                        <span class="jfa-profile-badge jfa-profile-badge--success">
                            <span class="material-symbols-outlined">verified</span> Email verified
                        </span>
                    @else
                        <span class="jfa-profile-badge jfa-profile-badge--muted">
                            <span class="material-symbols-outlined">mail</span> Email not verified
                        </span>
                    @endif
                    @if($user->google_id)
                        <span class="jfa-profile-badge">
                            <span class="material-symbols-outlined">link</span> Google linked
                        </span>
                    @endif
                    <span class="jfa-profile-badge jfa-profile-badge--muted">
                        <span class="material-symbols-outlined">calendar_month</span>
                        Member since {{ $user->created_at?->format('M Y') ?? '—' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="jfa-stat-grid jfa-profile-stats">
            <div class="jfa-stat"><strong>{{ $stats['bookings'] }}</strong><span>Bookings</span></div>
            <div class="jfa-stat"><strong>{{ $stats['wishlist'] }}</strong><span>Wishlist</span></div>
            <div class="jfa-stat"><strong>{{ $stats['travellers'] }}</strong><span>Saved travellers</span></div>
            <div class="jfa-stat"><strong>{{ $stats['referrals'] }}</strong><span>Referrals</span></div>
        </div>

        <form method="post" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="jfa-profile-layout">
            @csrf
            @method('PUT')

            <div class="jfa-profile-main">
                <div class="jfa-profile-form-card">
                    <section class="jfa-profile-section">
                        <header class="jfa-profile-section__head">
                            <span class="material-symbols-outlined">person</span>
                            <div>
                                <h3>Personal details</h3>
                                <p>Used for bookings, tickets and account communication.</p>
                            </div>
                        </header>
                        <div class="jfa-profile-fields jfa-profile-fields--2">
                            <div class="jfa-profile-field">
                                <label class="jfa-label" for="name">Full name</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" @class(['is-invalid' => $errors->has('name')])>
                                @error('name')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="jfa-profile-field">
                                <label class="jfa-label" for="phone">Phone number</label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" autocomplete="tel" placeholder="+91 98765 43210" @class(['is-invalid' => $errors->has('phone')])>
                                @error('phone')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="jfa-profile-field jfa-profile-field--full">
                                <label class="jfa-label" for="email">Email address</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email" @class(['is-invalid' => $errors->has('email')])>
                                @error('email')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </section>

                    <section class="jfa-profile-section">
                        <header class="jfa-profile-section__head">
                            <span class="material-symbols-outlined">flight</span>
                            <div>
                                <h3>Travel profile</h3>
                                <p>Optional — helps pre-fill passenger details faster.</p>
                            </div>
                        </header>
                        <div class="jfa-profile-fields jfa-profile-fields--2">
                            <div class="jfa-profile-field">
                                <label class="jfa-label" for="date_of_birth">Date of birth</label>
                                <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" max="{{ now()->subDay()->format('Y-m-d') }}" @class(['is-invalid' => $errors->has('date_of_birth')])>
                                @error('date_of_birth')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="jfa-profile-field">
                                <label class="jfa-label" for="gender">Gender</label>
                                <select id="gender" name="gender" @class(['is-invalid' => $errors->has('gender')])>
                                    @foreach($genderOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(old('gender', $user->gender ?? '') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('gender')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="jfa-profile-field jfa-profile-field--full">
                                <label class="jfa-label" for="nationality">Nationality</label>
                                <input id="nationality" name="nationality" type="text" value="{{ old('nationality', $user->nationality) }}" placeholder="e.g. Indian" maxlength="80" @class(['is-invalid' => $errors->has('nationality')])>
                                @error('nationality')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </section>

                    <div class="jfa-profile-form-actions">
                        <button type="submit" class="btn jfa-profile-save">Save changes</button>
                        <a href="{{ route('account.dashboard') }}" class="btn secondary">Cancel</a>
                    </div>
                </div>
            </div>

            <aside class="jfa-profile-aside">
                <div class="jfa-profile-side-card">
                    <h3>Profile photo</h3>
                    <div class="jfa-profile-photo">
                        <div class="jfa-profile-photo__preview" id="jfa-profile-photo-preview" aria-hidden="true">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="">
                            @else
                                <span>{{ $initials }}</span>
                            @endif
                        </div>
                        <div class="jfa-profile-photo__fields">
                            <label class="jfa-profile-upload-btn" for="avatar_file">
                                <span class="material-symbols-outlined">upload</span>
                                Choose photo
                            </label>
                            <input id="avatar_file" name="avatar_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif" class="jfa-profile-upload-input" @class(['is-invalid' => $errors->has('avatar_file')])>
                            <p class="jfa-profile-photo__hint">Square photo works best. Max 4 MB.</p>
                            @error('avatar_file')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            @if($user->avatar)
                                <label class="jfa-profile-photo__remove">
                                    <input type="checkbox" name="clear_avatar" value="1" @checked(old('clear_avatar'))>
                                    Remove photo
                                </label>
                            @endif
                        </div>
                    </div>
                </div>

                @if($referralShareUrl)
                    <div class="jfa-profile-side-card">
                        <h3>Refer &amp; earn</h3>
                        <p class="jfa-profile-side-card__text">Share your code when friends sign up.</p>
                        <div class="jfa-profile-referral-code">
                            <code id="jfa-referral-code">{{ $user->referral_code }}</code>
                            <button type="button" class="jfa-profile-copy-btn" data-copy-target="jfa-referral-code" aria-label="Copy referral code">
                                <span class="material-symbols-outlined">content_copy</span>
                            </button>
                        </div>
                        <button type="button" class="btn secondary jfa-profile-copy-link" data-copy-text="{{ $referralShareUrl }}">
                            <span class="material-symbols-outlined">link</span> Copy invite link
                        </button>
                        <p class="jfa-profile-side-card__meta">{{ $stats['referrals'] }} friend{{ $stats['referrals'] === 1 ? '' : 's' }} joined</p>
                    </div>
                @endif

                <div class="jfa-profile-side-card">
                    <h3>Security</h3>
                    <p class="jfa-profile-side-card__text">Keep your account safe with a strong, unique password.</p>
                    <a href="{{ route('account.password.edit') }}" class="btn secondary jfa-profile-side-link">
                        <span class="material-symbols-outlined">lock</span> Change password
                    </a>
                </div>

                <div class="jfa-profile-side-card jfa-profile-side-card--links">
                    <h3>Quick links</h3>
                    <a href="{{ route('account.bookings.index') }}"><span class="material-symbols-outlined">luggage</span> My bookings</a>
                    <a href="{{ route('account.saved-travellers.index') }}"><span class="material-symbols-outlined">group</span> Saved travellers</a>
                    <a href="{{ route('account.wishlist.index') }}"><span class="material-symbols-outlined">favorite</span> Wishlist</a>
                    <a href="{{ route('account.announcements.index') }}"><span class="material-symbols-outlined">notifications</span> Notifications</a>
                </div>
            </aside>
        </form>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var fileInput = document.getElementById('avatar_file');
    var preview = document.getElementById('jfa-profile-photo-preview');
    var heroAvatar = document.getElementById('jfa-profile-hero-avatar');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            var file = fileInput.files && fileInput.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (event) {
                var html = '<img src="' + event.target.result + '" alt="">';
                if (preview) preview.innerHTML = html;
                if (heroAvatar) heroAvatar.innerHTML = html;
            };
            reader.readAsDataURL(file);
        });
    }

    document.querySelectorAll('[data-copy-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var el = document.getElementById(btn.getAttribute('data-copy-target'));
            if (!el || !navigator.clipboard) return;
            navigator.clipboard.writeText(el.textContent.trim()).then(function () {
                btn.classList.add('is-copied');
                setTimeout(function () { btn.classList.remove('is-copied'); }, 1500);
            });
        });
    });

    document.querySelectorAll('[data-copy-text]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.getAttribute('data-copy-text');
            if (!text || !navigator.clipboard) return;
            navigator.clipboard.writeText(text).then(function () {
                var original = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined">check</span> Copied!';
                setTimeout(function () { btn.innerHTML = original; }, 1500);
            });
        });
    });
})();
</script>
@endpush
