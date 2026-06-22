@extends('layouts.app')

@section('body_class', 'page-contact')

@section('title', 'Contact us — Jet Fly Airways')

@section('meta_description', 'Get in touch with Jet Fly Airways for bookings, refunds, and travel support. We respond within one business day.')

@section('full')
    @include('partials.jfa-page-hero', [
        'title' => 'Contact us',
        'description' => $pageBanner?->subtitle ?: 'Questions about bookings, refunds, or travel plans? Our support team is here to help — usually within one business day.',
        'icon' => 'support_agent',
        'accentColor' => '#003B95',
        'bannerImage' => $pageBanner?->imageUrl(),
        'heroClass' => 'jfa-cms-hero jfa-contact-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Contact us'],
        ],
    ])
@endsection

@section('content')
    @php
        $settings = $siteSetting ?? null;
        $phones = $settings?->supportPhoneList() ?? [['label' => 'Support', 'phone' => '+91 1800-000-0000']];
        $emails = $settings?->supportEmailList() ?? [['label' => 'Support', 'email' => 'support@jetflyairways.com']];
        $addresses = $settings?->officeAddressList() ?? [];
        $chatUrl = $settings?->live_chat_url;
        $tawkEnabled = $settings?->tawkEnabled();
        $defaultName = old('name', auth()->user()?->name);
        $defaultEmail = old('email', auth()->user()?->email);
        $defaultPhone = old('phone', auth()->user()?->phone ?? '');
    @endphp

    <div class="jfa-contact-panel">
        <div class="jfa-contact-shell">
            <aside class="jfa-contact-aside" aria-label="Contact information">
                <div class="jfa-contact-aside__head">
                    <span class="jfa-contact-aside__eyebrow">Support channels</span>
                    <h2>Reach our team</h2>
                    <p>For urgent booking changes, include your booking code in the message.</p>
                </div>

                <div class="jfa-contact-methods">
                    @foreach($phones as $phoneRow)
                        <a class="jfa-contact-method" href="tel:{{ preg_replace('/\s+/', '', $phoneRow['phone']) }}">
                            <span class="jfa-contact-method__icon"><span class="material-symbols-outlined filled">call</span></span>
                            <span class="jfa-contact-method__body">
                                <strong>{{ $phoneRow['label'] }}</strong>
                                <span>{{ $phoneRow['phone'] }}</span>
                            </span>
                            <span class="jfa-contact-method__action">Call</span>
                        </a>
                    @endforeach

                    @foreach($emails as $mailRow)
                        <a class="jfa-contact-method" href="mailto:{{ $mailRow['email'] }}">
                            <span class="jfa-contact-method__icon jfa-contact-method__icon--mail"><span class="material-symbols-outlined filled">mail</span></span>
                            <span class="jfa-contact-method__body">
                                <strong>{{ $mailRow['label'] }}</strong>
                                <span>{{ $mailRow['email'] }}</span>
                            </span>
                            <span class="jfa-contact-method__action">Email</span>
                        </a>
                    @endforeach

                    @foreach($addresses as $addrRow)
                        <div class="jfa-contact-method jfa-contact-method--static">
                            <span class="jfa-contact-method__icon jfa-contact-method__icon--location"><span class="material-symbols-outlined filled">location_on</span></span>
                            <span class="jfa-contact-method__body">
                                <strong>{{ $addrRow['label'] }}</strong>
                                <span>{!! nl2br(e($addrRow['address'])) !!}</span>
                            </span>
                        </div>
                    @endforeach

                    @if($tawkEnabled)
                        <button type="button" class="jfa-contact-method jfa-contact-method--accent" data-open-tawk style="width:100%;text-align:left;" onclick="return window.jfaOpenTawkChat && window.jfaOpenTawkChat(event);">
                            <span class="jfa-contact-method__icon jfa-contact-method__icon--chat"><span class="material-symbols-outlined filled">chat</span></span>
                            <span class="jfa-contact-method__body">
                                <strong>Live chat</strong>
                                <span>Chat with support online</span>
                            </span>
                            <span class="jfa-contact-method__action">Open</span>
                        </button>
                    @elseif(filled($chatUrl))
                        <a class="jfa-contact-method jfa-contact-method--accent" href="{{ $chatUrl }}" target="_blank" rel="noopener noreferrer">
                            <span class="jfa-contact-method__icon jfa-contact-method__icon--chat"><span class="material-symbols-outlined filled">chat</span></span>
                            <span class="jfa-contact-method__body">
                                <strong>Live chat</strong>
                                <span>Chat with support online</span>
                            </span>
                            <span class="jfa-contact-method__action">Open</span>
                        </a>
                    @endif
                </div>

                <div class="jfa-contact-trust">
                    <span class="jfa-contact-trust__pill"><span class="material-symbols-outlined filled">schedule</span> 1 business day</span>
                    <span class="jfa-contact-trust__pill"><span class="material-symbols-outlined filled">verified_user</span> Secure</span>
                    <span class="jfa-contact-trust__pill"><span class="material-symbols-outlined filled">support_agent</span> 24/7 phone</span>
                </div>
            </aside>

            <div class="jfa-contact-main">
                <div class="jfa-contact-form-card">
                    <div class="jfa-contact-form-card__head">
                        <h2>Send a message</h2>
                        <p>Fill in the form and we will get back to you by email or phone.</p>
                    </div>

                    @if(session('status'))
                        <div class="jfa-contact-alert jfa-contact-alert--success" role="status">
                            <span class="material-symbols-outlined filled" aria-hidden="true">check_circle</span>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="jfa-contact-alert jfa-contact-alert--error" role="alert">
                            <span class="material-symbols-outlined filled" aria-hidden="true">error</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="post" action="{{ route('contact.store') }}" class="jfa-contact-form">
                        @csrf
                        <div class="jfa-contact-form__grid">
                            <label class="jfa-contact-field">
                                <span class="jfa-contact-field__label">Full name</span>
                                <input class="jfa-contact-field__input" type="text" name="name" value="{{ $defaultName }}" required autocomplete="name" placeholder="Your name">
                                @error('name')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="jfa-contact-field">
                                <span class="jfa-contact-field__label">Email address</span>
                                <input class="jfa-contact-field__input" type="email" name="email" value="{{ $defaultEmail }}" required autocomplete="email" placeholder="you@example.com">
                                @error('email')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="jfa-contact-field">
                                <span class="jfa-contact-field__label">Phone <em>(optional)</em></span>
                                <input class="jfa-contact-field__input" type="text" name="phone" value="{{ $defaultPhone }}" autocomplete="tel" placeholder="+91 …">
                                @error('phone')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="jfa-contact-field">
                                <span class="jfa-contact-field__label">Subject <em>(optional)</em></span>
                                <input class="jfa-contact-field__input" type="text" name="subject" value="{{ old('subject') }}" placeholder="Booking change, refund, general enquiry">
                                @error('subject')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="jfa-contact-field jfa-contact-form__full">
                                <span class="jfa-contact-field__label">Message</span>
                                <textarea class="jfa-contact-field__input jfa-contact-field__input--area" name="message" rows="5" required placeholder="Tell us how we can help. Include your booking code if relevant.">{{ old('message') }}</textarea>
                                @error('message')<span class="jfa-field-error">{{ $message }}</span>@enderror
                            </label>
                        </div>
                        <div class="jfa-contact-form__actions">
                            <button type="submit" class="btn jfa-contact-submit">
                                <span class="material-symbols-outlined" aria-hidden="true">send</span>
                                Send message
                            </button>
                            <p class="jfa-contact-form__note">By submitting, you agree we may contact you about this request.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <section class="jfa-contact-help" aria-label="Quick help links">
        <h3>Quick help</h3>
        <div class="jfa-contact-help__grid">
            <a class="jfa-contact-help__link" href="{{ route('faq.index') }}">
                <span class="material-symbols-outlined">quiz</span>
                <span>
                    <strong>FAQs</strong>
                    <small>Common booking &amp; refund answers</small>
                </span>
            </a>
            <a class="jfa-contact-help__link" href="{{ route('pages.show', 'help') }}">
                <span class="material-symbols-outlined">help</span>
                <span>
                    <strong>Help centre</strong>
                    <small>Guides for flights, hotels &amp; packages</small>
                </span>
            </a>
            @auth
                <a class="jfa-contact-help__link" href="{{ route('account.bookings.index') }}">
                    <span class="material-symbols-outlined">luggage</span>
                    <span>
                        <strong>My bookings</strong>
                        <small>View trips on your account</small>
                    </span>
                </a>
            @else
                <a class="jfa-contact-help__link" href="{{ route('login') }}">
                    <span class="material-symbols-outlined">login</span>
                    <span>
                        <strong>Sign in</strong>
                        <small>Access bookings &amp; support history</small>
                    </span>
                </a>
            @endauth
        </div>
    </section>
@endsection
