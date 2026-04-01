@if($welcomePopup)
    <div id="jetfly-welcome-popup" role="dialog" aria-modal="true" aria-labelledby="jetfly-popup-title" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,.55);align-items:center;justify-content:center;padding:16px;">
        <div style="background:#fff;border-radius:16px;max-width:420px;width:100%;box-shadow:0 24px 60px rgba(15,23,42,.25);overflow:hidden;position:relative;">
            <button type="button" id="jetfly-popup-close" aria-label="Close" style="position:absolute;top:10px;right:10px;width:36px;height:36px;border:none;border-radius:10px;background:#f1f5f9;cursor:pointer;font-size:18px;line-height:1;">×</button>
            @php $popupImg = \App\Support\PublicImageStorage::url($welcomePopup->image); @endphp
            @if($popupImg)
                <div style="aspect-ratio:2/1;background:#e2e8f0;">
                    <img src="{{ $popupImg }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                </div>
            @endif
            <div style="padding:20px 20px 22px;">
                @if($welcomePopup->title)
                    <h2 id="jetfly-popup-title" style="margin:0 0 10px;font-size:1.2rem;color:#0b2f71;">{{ $welcomePopup->title }}</h2>
                @endif
                @if($welcomePopup->message)
                    <p style="margin:0;font-size:15px;color:#475569;line-height:1.55;">{!! nl2br(e($welcomePopup->message)) !!}</p>
                @endif
                <div style="margin-top:18px;display:flex;gap:10px;flex-wrap:wrap;">
                    @if($welcomePopup->redirect_link && $welcomePopup->button_text)
                        <a href="{{ $welcomePopup->redirect_link }}" class="btn" style="display:inline-block;">{{ $welcomePopup->button_text }}</a>
                    @endif
                    <button type="button" class="btn secondary" id="jetfly-popup-dismiss" style="border:none;cursor:pointer;">Not now</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
    (function () {
        var root = document.getElementById('jetfly-welcome-popup');
        if (!root) return;
        var key = 'jetfly_popup_dismissed_{{ $welcomePopup->id }}';
        try {
            if (sessionStorage.getItem(key)) return;
        } catch (e) {}
        root.style.display = 'flex';
        function close() {
            root.style.display = 'none';
            try { sessionStorage.setItem(key, '1'); } catch (e) {}
        }
        document.getElementById('jetfly-popup-close').addEventListener('click', close);
        document.getElementById('jetfly-popup-dismiss').addEventListener('click', close);
        root.addEventListener('click', function (e) { if (e.target === root) close(); });
    })();
    </script>
    @endpush
@endif
