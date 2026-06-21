@php
    /** @var \App\Models\Announcement|null $announcement */
    $tz = config('app.timezone');
    $nowLocal = now()->timezone($tz)->format('Y-m-d\TH:i');
    $isAlreadyLive = isset($announcement)
        && $announcement->is_active
        && $announcement->published_at
        && $announcement->published_at->lte(now());
    $defaultPublished = old(
        'published_at',
        isset($announcement) && $announcement->published_at
            ? $announcement->published_at->timezone($tz)->format('Y-m-d\TH:i')
            : $nowLocal
    );
    if (! $isAlreadyLive && $defaultPublished < $nowLocal) {
        $defaultPublished = $nowLocal;
    }
@endphp
<div class="admin-form-grid">
    <label class="admin-field-full">Title
        <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required maxlength="200" placeholder="e.g. New payment options available">
    </label>

    <label class="admin-field-full">Message
        <textarea name="body" rows="6" maxlength="10000" placeholder="Optional details shown in the customer notification inbox">{{ old('body', $announcement->body ?? '') }}</textarea>
    </label>

    <label class="admin-field-full">External link (optional)
        <input type="url" name="link" value="{{ old('link', $announcement->link ?? '') }}" maxlength="500" placeholder="https://… — opens when customer taps Open link">
    </label>

    @if($isAlreadyLive)
        <div class="admin-field-full" style="padding:12px 14px;border:1px solid #dbeafe;border-radius:12px;background:#eff6ff;color:#1e3a8a;font-size:13px;line-height:1.5;">
            <strong>Published on:</strong>
            {{ $announcement->published_at->timezone($tz)->format('M j, Y g:i A') }}
            <span style="display:block;margin-top:4px;color:#3b82f6;">This announcement is already live. The publish time cannot be moved to the past.</span>
        </div>
        <input type="hidden" name="published_at" value="{{ $defaultPublished }}">
    @else
        <label class="admin-field-full">Publish date &amp; time
            <input
                type="datetime-local"
                id="announcement-published-at"
                name="published_at"
                value="{{ $defaultPublished }}"
                step="60"
                required
            >
            <span style="display:block;margin-top:6px;font-size:12px;color:#64748b;font-weight:400;line-height:1.45;">
                Choose <strong>now</strong> to publish immediately, or a <strong>future</strong> date to schedule. Past dates are disabled in the calendar.
            </span>
            @error('published_at')
                <span style="display:block;margin-top:6px;font-size:12px;color:#b91c1c;font-weight:600;">{{ $message }}</span>
            @enderror
        </label>

        @push('scripts')
        <script>
        (function () {
            var input = document.getElementById('announcement-published-at');
            if (!input) return;

            function pad(n) { return String(n).padStart(2, '0'); }

            function formatLocal(date) {
                return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()) + 'T' + pad(date.getHours()) + ':' + pad(date.getMinutes());
            }

            function minNow() {
                var now = new Date();
                now.setSeconds(0, 0);
                return formatLocal(now);
            }

            function enforceMin() {
                var min = minNow();
                input.min = min;
                if (!input.value || input.value < min) {
                    input.value = min;
                }
            }

            enforceMin();
            input.addEventListener('focus', enforceMin);
            input.addEventListener('click', enforceMin);
            input.addEventListener('change', enforceMin);
            input.addEventListener('input', function () {
                var min = input.min || minNow();
                if (input.value && input.value < min) {
                    input.value = min;
                }
            });

            var form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function (event) {
                    enforceMin();
                    if (input.value < input.min) {
                        event.preventDefault();
                        window.alert('Publish date cannot be in the past. Please choose now or a future time.');
                    }
                });
            }

            setInterval(enforceMin, 30000);
        })();
        </script>
        @endpush
    @endif

    <div class="admin-field-full">
        @include('admin.partials.toggle', [
            'name' => 'is_active',
            'label' => 'Active',
            'hint' => 'Must be on to show customers. With a future publish date it stays hidden until that time.',
            'checked' => old('is_active', ($announcement->is_active ?? true) ? '1' : '0') === '1',
        ])
    </div>
</div>
