@if($welcomePopup)
    @php
        $popupImg = \App\Support\PublicImageStorage::url($welcomePopup->image);
        $redirectUrl = filled($welcomePopup->redirect_link) ? $welcomePopup->redirect_link : null;
    @endphp
    <div id="jetfly-welcome-popup" class="jetfly-popup-root" role="dialog" aria-modal="true" @if($welcomePopup->title) aria-labelledby="jetfly-popup-title" @else aria-label="Promotion" @endif>
        <div class="jetfly-popup-backdrop"></div>
        <div class="jetfly-popup-card {{ $redirectUrl ? 'jetfly-popup-card--clickable' : '' }}" @if($redirectUrl) data-popup-href="{{ $redirectUrl }}" @endif>
            <button type="button" class="jetfly-popup-close" id="jetfly-popup-close" aria-label="Close">
                <span class="material-symbols-outlined" aria-hidden="true">close</span>
            </button>

            <div class="jetfly-popup-stage {{ (filled($welcomePopup->title) || filled($welcomePopup->message)) ? 'jetfly-popup-stage--has-copy' : '' }}" id="jetfly-popup-stage">
                @if($popupImg)
                    <img src="{{ $popupImg }}" alt="" class="jetfly-popup-img">
                @else
                    <div class="jetfly-popup-img jetfly-popup-img--fallback" aria-hidden="true"></div>
                @endif

                @if(filled($welcomePopup->title) || filled($welcomePopup->message))
                    <div class="jetfly-popup-copy">
                        @if($welcomePopup->title)
                            <h2 id="jetfly-popup-title" class="jetfly-popup-title">{{ $welcomePopup->title }}</h2>
                        @endif
                        @if($welcomePopup->message)
                            <p class="jetfly-popup-message">{!! nl2br(e($welcomePopup->message)) !!}</p>
                        @endif
                    </div>
                @endif
            </div>

            <button type="button" class="jetfly-popup-dismiss" id="jetfly-popup-dismiss">Not now</button>
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
        document.body.classList.add('jetfly-popup-open');

        function close() {
            root.classList.remove('is-open');
            document.body.classList.remove('jetfly-popup-open');
            try { sessionStorage.setItem(key, '1'); } catch (e) {}
        }

        document.getElementById('jetfly-popup-close').addEventListener('click', close);
        document.getElementById('jetfly-popup-dismiss').addEventListener('click', close);

        var card = root.querySelector('.jetfly-popup-card');
        var href = card && card.getAttribute('data-popup-href');
        if (card && href) {
            card.addEventListener('click', function (event) {
                if (event.target.closest('.jetfly-popup-close, .jetfly-popup-dismiss')) return;
                close();
                window.location.href = href;
            });
        }
    })();
    </script>
    @endpush
@endif
