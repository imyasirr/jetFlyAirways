@if($welcomePopup)
    @php $popupImg = \App\Support\PublicImageStorage::url($welcomePopup->image); @endphp
    <div id="jetfly-welcome-popup" class="jetfly-popup-root" role="dialog" aria-modal="true" @if($welcomePopup->title) aria-labelledby="jetfly-popup-title" @else aria-label="Announcement" @endif>
        <div class="jetfly-popup-backdrop" data-jetfly-popup-close></div>
        <div class="jetfly-popup-card">
            <button type="button" class="jetfly-popup-close" id="jetfly-popup-close" aria-label="Close">&times;</button>
            <div class="jetfly-popup-visual">
                @if($popupImg)
                    <img src="{{ $popupImg }}" alt="" class="jetfly-popup-img">
                @else
                    <div class="jetfly-popup-img jetfly-popup-img--fallback" aria-hidden="true"></div>
                @endif
                <div class="jetfly-popup-overlay">
                    @if($welcomePopup->title)
                        <h2 id="jetfly-popup-title" class="jetfly-popup-title">{{ $welcomePopup->title }}</h2>
                    @endif
                    @if($welcomePopup->message)
                        <p class="jetfly-popup-message">{!! nl2br(e($welcomePopup->message)) !!}</p>
                    @endif
                    <div class="jetfly-popup-actions">
                        @if($welcomePopup->redirect_link)
                            <a href="{{ $welcomePopup->redirect_link }}" class="jetfly-popup-btn jetfly-popup-btn--primary">{{ $welcomePopup->button_text ?: 'Continue' }}</a>
                        @endif
                        <button type="button" class="jetfly-popup-btn jetfly-popup-btn--ghost" id="jetfly-popup-dismiss">{{ $welcomePopup->redirect_link ? 'Not now' : 'Close' }}</button>
                    </div>
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
        root.classList.add('is-open');
        function close() {
            root.classList.remove('is-open');
            try { sessionStorage.setItem(key, '1'); } catch (e) {}
        }
        document.getElementById('jetfly-popup-close').addEventListener('click', close);
        document.getElementById('jetfly-popup-dismiss').addEventListener('click', close);
        root.querySelectorAll('[data-jetfly-popup-close]').forEach(function (el) {
            el.addEventListener('click', close);
        });
    })();
    </script>
    @endpush
@endif
