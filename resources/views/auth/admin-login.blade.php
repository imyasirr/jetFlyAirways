<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Sign In — Jet Fly Airways</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #003B95;
            --blue-2: #005BBF;
            --blue-3: #1A73E8;
            --yellow: #FFE16D;
            --ink: #1A1C1C;
            --muted: #515F78;
            --border: #C1C6D6;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            color: var(--ink);
            background: linear-gradient(135deg, #00276b 0%, var(--blue) 45%, var(--blue-2) 100%);
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            opacity: 0.08;
            pointer-events: none;
            background-image: radial-gradient(#ffffff 2px, transparent 2px);
            background-size: 34px 34px;
        }

        .material-symbols-outlined {
            font-family: "Material Symbols Outlined";
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            display: inline-block;
            vertical-align: middle;
            -webkit-font-smoothing: antialiased;
        }

        .shell {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            max-width: 960px;
            min-height: 560px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
        }

        /* Left brand panel */

        .brand-pane {
            position: relative;
            flex: 1 1 46%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 44px 40px;
            color: #fff;
            background:
                linear-gradient(160deg, rgba(0, 28, 80, 0.92) 0%, rgba(0, 59, 149, 0.88) 55%, rgba(26, 115, 232, 0.82) 100%),
                url('https://images.unsplash.com/photo-1436491865332-7a61a1092e56?auto=format&fit=crop&q=80&w=1200') center / cover no-repeat;
        }

        .brand-top { display: flex; align-items: center; gap: 12px; }

        .brand-logo {
            height: 46px;
            width: auto;
            max-width: 130px;
            object-fit: contain;
            background: #fff;
            border-radius: 10px;
            padding: 4px 9px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
        }

        .brand-name {
            font-family: "Montserrat", sans-serif;
            font-size: 20px;
            font-weight: 700;
            line-height: 1.1;
        }

        .brand-name small {
            display: block;
            margin-top: 3px;
            color: var(--yellow);
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        .brand-mid h1 {
            margin: 0 0 14px;
            font-family: "Montserrat", sans-serif;
            font-size: clamp(26px, 3.2vw, 36px);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .brand-mid p {
            margin: 0;
            max-width: 340px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 14.5px;
            line-height: 1.7;
        }

        .brand-points { display: grid; gap: 12px; }

        .brand-point {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.92);
        }

        .brand-point .material-symbols-outlined {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 19px;
            color: var(--yellow);
        }

        /* Right form panel */

        .form-pane {
            flex: 1 1 54%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px clamp(28px, 5vw, 56px);
            background: #fff;
        }

        .form-eyebrow {
            margin: 0 0 8px;
            color: var(--blue-2);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .form-pane h2 {
            margin: 0 0 6px;
            font-family: "Montserrat", sans-serif;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--ink);
        }

        .form-sub {
            margin: 0 0 26px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .alert-error {
            margin: 0 0 18px;
            padding: 12px 14px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13.5px;
            line-height: 1.5;
        }

        label {
            display: block;
            margin: 0 0 6px;
            color: var(--muted);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .field { position: relative; margin-bottom: 16px; }

        .field .material-symbols-outlined {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #727785;
            font-size: 20px;
            pointer-events: none;
        }

        .field input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            color: var(--ink);
            font: inherit;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.15s ease;
        }

        .field input::placeholder { color: #9aa1b1; font-weight: 500; }

        .field input:focus {
            outline: none;
            border-color: transparent;
            box-shadow: 0 0 0 2px var(--blue-2);
        }

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 2px 0 20px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--blue-2);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 15px 20px;
            border: 0;
            border-radius: 12px;
            background: var(--blue);
            color: #fff;
            font-family: "Montserrat", sans-serif;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(0, 59, 149, 0.35);
            transition: all 0.15s ease;
        }

        .btn-submit:hover { background: var(--blue-2); }
        .btn-submit:active { transform: scale(0.98); }

        .form-foot {
            margin: 22px 0 0;
            text-align: center;
            color: var(--muted);
            font-size: 13.5px;
        }

        .form-foot a {
            color: var(--blue-2);
            font-weight: 700;
            text-decoration: none;
        }

        .form-foot a:hover { text-decoration: underline; }

        .form-secure {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 18px;
            color: #9aa1b1;
            font-size: 12px;
        }

        .form-secure .material-symbols-outlined { font-size: 15px; }

        @media (max-width: 820px) {
            .shell { flex-direction: column; min-height: 0; max-width: 480px; }
            .brand-pane { padding: 30px 28px; gap: 24px; }
            .brand-mid h1 { font-size: 24px; }
            .brand-points { display: none; }
            .form-pane { padding: 32px 26px 36px; }
        }
    </style>
</head>
<body>
    @include('partials.ui-extras')
    @php
        $siteSetting = $siteSetting ?? \App\Models\SiteSetting::query()->first();
        $logoUrl = ($siteSetting ?? null)?->logo_image
            ? \App\Support\PublicImageStorage::url($siteSetting->logo_image)
            : null;
        $bName = $siteSetting?->brand_name ?? 'Jet Fly Airways';
        $bTag = $siteSetting?->brand_tagline ?? 'Book · Fly · Stay';
    @endphp
    <div class="shell">
        <div class="brand-pane">
            <div class="brand-top">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $bName }}" class="brand-logo">
                @endif
                <span class="brand-name">{{ $bName }}<small>{{ $bTag }}</small></span>
            </div>
            <div class="brand-mid">
                <h1>Control Center</h1>
                <p>Manage flights, hotels, packages, bookings and every corner of the Jet Fly Airways platform from one place.</p>
            </div>
            <div class="brand-points">
                <span class="brand-point"><span class="material-symbols-outlined">dashboard</span> Live dashboard &amp; reports</span>
                <span class="brand-point"><span class="material-symbols-outlined">flight</span> Inventory &amp; booking management</span>
                <span class="brand-point"><span class="material-symbols-outlined">shield_lock</span> Secured admin-only access</span>
            </div>
        </div>
        <div class="form-pane">
            <p class="form-eyebrow">Admin Panel</p>
            <h2>Sign in to continue</h2>
            <p class="form-sub">Enter the credentials for your Jet Fly admin account.</p>

            @if ($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ route('admin.login') }}">
                @csrf
                <label for="email">Email</label>
                <div class="field">
                    <span class="material-symbols-outlined" aria-hidden="true">mail</span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="admin@jetflyairways.com" required autocomplete="username" autofocus>
                </div>

                <label for="password">Password</label>
                <div class="field">
                    <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                    <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </div>

                <div class="row-between">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <p class="form-foot">Customer? <a href="{{ route('login') }}">Sign in to your account</a> · <a href="{{ route('home') }}">Back to website</a></p>
            <p class="form-secure"><span class="material-symbols-outlined" aria-hidden="true">lock</span> Protected area — authorised personnel only</p>
        </div>
    </div>
</body>
</html>
