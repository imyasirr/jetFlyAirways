{{-- Universal page loader (rotating flight) + password show/hide toggle --}}
<noscript><style>#jf-loader { display: none !important; }</style></noscript>
<div id="jf-loader" class="is-active" aria-hidden="true" role="status">
    <div class="jf-loader-inner">
        <span class="jf-loader-spin" aria-hidden="true">
            <svg class="jf-loader-plane" viewBox="0 0 24 24" width="26" height="26" fill="currentColor" aria-hidden="true">
                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
            </svg>
        </span>
        <span class="jf-loader-brand">Jet Fly Airways</span>
        {{-- <span class="jf-loader-sub">Loading…</span> --}}
    </div>
</div>
<style>
    #jf-loader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.88);
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }

    #jf-loader.is-active {
        opacity: 1;
        visibility: visible;
    }

    .jf-loader-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-align: center;
    }

    .jf-loader-spin {
        position: relative;
        width: 84px;
        height: 84px;
        margin-bottom: 14px;
        border: 2px dashed rgba(0, 59, 149, 0.35);
        border-radius: 50%;
        animation: jf-spin 1.5s linear infinite;
    }

    .jf-loader-plane {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%) rotate(90deg);
        color: #003B95;
        filter: drop-shadow(0 2px 4px rgba(0, 59, 149, 0.35));
    }

    @keyframes jf-spin {
        to { transform: rotate(360deg); }
    }

    .jf-loader-brand {
        font-family: "Montserrat", "Inter", system-ui, sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: #003B95;
        letter-spacing: -0.01em;
    }

    .jf-loader-sub {
        font-family: "Inter", system-ui, sans-serif;
        font-size: 12.5px;
        font-weight: 600;
        color: #515F78;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    /* Password show/hide */
    .jf-pw {
        position: relative;
        display: block;
        width: 100%;
    }

    .jf-pw-toggle {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #727785;
        cursor: pointer;
        line-height: 1;
    }

    .jf-pw-toggle:hover {
        color: #003B95;
        background: rgba(0, 59, 149, 0.07);
    }
</style>
<script>
(function () {
    /* ---------- Universal loader ---------- */
    var loader = document.getElementById('jf-loader');

    function showLoader() {
        if (loader) loader.classList.add('is-active');
    }

    function hideLoader() {
        if (loader) loader.classList.remove('is-active');
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (e.defaultPrevented) return;
        if (form.target && form.target === '_blank') return;
        if (form.hasAttribute('data-no-loader')) return;
        showLoader();
    }, true);

    /* Hide when restored from back/forward cache */
    window.addEventListener('pageshow', hideLoader);

    /* Initial page load: hide as soon as the DOM is ready (single loader cycle) */
    if (document.readyState !== 'loading') {
        hideLoader();
    } else {
        document.addEventListener('DOMContentLoaded', hideLoader);
        window.addEventListener('load', hideLoader);
    }
    setTimeout(hideLoader, 6000);

    /* ---------- Password show / hide ---------- */
    var eyeOn = '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 17 4.5 12 4.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>';
    var eyeOff = '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78 3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>';

    function attachPasswordToggles() {
        document.querySelectorAll('input[type="password"]').forEach(function (input) {
            if (input.closest('.jf-pw') || input.hasAttribute('data-no-eye')) return;

            var wrap = document.createElement('span');
            wrap.className = 'jf-pw';
            var mb = window.getComputedStyle(input).marginBottom;
            if (mb && mb !== '0px') {
                wrap.style.marginBottom = mb;
            }
            input.parentNode.insertBefore(wrap, input);
            wrap.appendChild(input);
            input.style.marginBottom = '0';
            input.style.paddingRight = '46px';

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'jf-pw-toggle';
            btn.setAttribute('aria-label', 'Show password');
            btn.innerHTML = eyeOn;
            btn.addEventListener('click', function () {
                var hide = input.type === 'text';
                input.type = hide ? 'password' : 'text';
                btn.innerHTML = hide ? eyeOn : eyeOff;
                btn.setAttribute('aria-label', hide ? 'Show password' : 'Hide password');
                input.focus();
            });
            wrap.appendChild(btn);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attachPasswordToggles);
    } else {
        attachPasswordToggles();
    }
})();
</script>
