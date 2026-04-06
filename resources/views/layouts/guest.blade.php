<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sign in') — Jet Fly Airways</title>
    <style>
        :root { --primary:#0b2f71; --secondary:#38bdf8; --accent:#f97316; --bg:#e8f0fc; --text:#0f172a; --muted:#64748b; }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; background:linear-gradient(160deg,#e8f0fc 0%,#f8fafc 50%,#e0f2fe 100%); color:var(--text); display:flex; align-items:center; justify-content:center; padding:24px; }
        a { color:inherit; text-decoration:none; }
        .guest-wrap { width:100%; max-width:420px; }
        .guest-brand { display:block; text-align:center; font-weight:800; font-size:1.25rem; color:var(--primary); margin-bottom:24px; letter-spacing:.02em; }
        .guest-card { background:#fff; border-radius:16px; padding:28px; box-shadow:0 20px 50px rgba(11,47,113,.12); border:1px solid rgba(11,47,113,.08); }
        .guest-card h1 { margin:0 0 8px; font-size:1.35rem; color:var(--primary); }
        .guest-card p { margin:0 0 20px; font-size:14px; color:var(--muted); }
        label { display:block; font-size:13px; font-weight:600; color:var(--muted); margin-bottom:6px; }
        input[type="email"], input[type="password"], input[type="text"], input[type="tel"] { width:100%; padding:12px 14px; border:1px solid #c9d5ef; border-radius:10px; font-size:15px; margin-bottom:14px; }
        input:focus { outline:2px solid var(--secondary); border-color:transparent; }
        .btn { width:100%; background:var(--accent); color:#fff; border:none; border-radius:12px; padding:12px 18px; font-weight:700; cursor:pointer; font-size:15px; margin-top:4px; }
        .btn:hover { filter:brightness(1.05); }
        .remember { display:flex; align-items:center; gap:8px; font-size:14px; margin-bottom:16px; }
        .form-actions { display:flex; flex-wrap:wrap; gap:10px; margin-top:4px; }
        .form-actions .btn { margin-top:0; }
        .auth-sep { margin:16px 0 10px; font-size:14px; text-align:center; color:var(--muted); }
        .auth-social { display:inline-block; width:100%; padding:10px; border-radius:12px; border:1px solid #c9d5ef; font-weight:700; color:var(--primary); text-align:center; }
        .auth-social:hover { background:#f8fafc; }
        .auth-link-center { margin-top:10px; font-size:14px; text-align:center; }
        .auth-link-center a { color:var(--primary); font-weight:700; }
        .alert { padding:10px 12px; border-radius:10px; font-size:14px; margin-bottom:16px; }
        .alert-error { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
        .alert-ok { background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; }
        .back { text-align:center; margin-top:20px; font-size:14px; }
        .back a { color:var(--primary); font-weight:600; }
        @media (max-width:640px) { .form-actions { flex-direction:column; } .form-actions .btn { width:100%; } }
    </style>
</head>
<body>
    <div class="guest-wrap">
        <a href="{{ route('home') }}" class="guest-brand">Jet Fly Airways</a>
        <div class="guest-card">
            @yield('content')
        </div>
        <p class="back"><a href="{{ route('home') }}">← Back to website</a></p>
    </div>
    @include('partials.flash-swal', ['swalConfirmColor' => '#0b2f71'])
</body>
</html>
