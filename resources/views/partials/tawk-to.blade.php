@if(($siteSetting ?? null)?->tawkEnabled())
@php
    $tawkPropertyId = e($siteSetting->tawkPropertyId());
    $tawkWidgetId = e($siteSetting->tawkWidgetId());
@endphp
<script>
window.Tawk_API = window.Tawk_API || {};
window.Tawk_LoadStart = new Date();
window.jfaTawkReady = false;
window.jfaTawkPendingOpen = false;

window.jfaHideTawkBubble = function () {
    var selectors = [
        '#tawk-bubble-container',
        '#tawkchat-container',
        '.tawk-min-container',
        '.tawk-max-container',
        '.tawk-button',
        'iframe[src*="tawk.to"]'
    ];
    selectors.forEach(function (selector) {
        document.querySelectorAll(selector).forEach(function (el) {
            el.style.setProperty('display', 'none', 'important');
            el.style.setProperty('visibility', 'hidden', 'important');
            el.style.setProperty('opacity', '0', 'important');
            el.style.setProperty('pointer-events', 'none', 'important');
            el.style.setProperty('z-index', '-1', 'important');
        });
    });
};

window.jfaOpenTawkChat = function (event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (window.jfaTawkReady && window.Tawk_API && typeof Tawk_API.maximize === 'function') {
        Tawk_API.maximize();
        return false;
    }
    window.jfaTawkPendingOpen = true;
    return false;
};

Tawk_API.onLoad = function () {
    window.jfaTawkReady = true;
    if (typeof Tawk_API.hideWidget === 'function') {
        Tawk_API.hideWidget();
    }
    window.jfaHideTawkBubble();
    document.documentElement.classList.add('jfa-tawk-ready');
    if (window.jfaTawkPendingOpen && typeof Tawk_API.maximize === 'function') {
        window.jfaTawkPendingOpen = false;
        Tawk_API.maximize();
    }
};

if (typeof MutationObserver !== 'undefined') {
    var tawkObserver = new MutationObserver(function () {
        window.jfaHideTawkBubble();
    });
    document.addEventListener('DOMContentLoaded', function () {
        tawkObserver.observe(document.body, { childList: true, subtree: true });
        window.jfaHideTawkBubble();
        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('[data-open-tawk]');
            if (trigger) {
                window.jfaOpenTawkChat(e);
            }
        }, true);
    });
}
</script>
<script async src="https://embed.tawk.to/{{ $tawkPropertyId }}/{{ $tawkWidgetId }}" charset="UTF-8" crossorigin="*"></script>
@endif
