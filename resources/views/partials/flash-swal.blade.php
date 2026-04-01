@php
    $swalColor = $swalConfirmColor ?? '#0b2f71';
    $errorList = $errors->any() ? $errors->all() : [];
    $flashOk = session('status');
    $flashErr = session('error');
@endphp
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swal === 'undefined') {
            return;
        }
        var color = @json($swalColor);
        var errs = @json($errorList);
        var ok = @json($flashOk);
        var singleErr = @json($flashErr);
        function esc(s) {
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
        if (errs.length) {
            Swal.fire({
                icon: 'error',
                title: @json(__('jetfly.swal_validation_title')),
                html: '<ul style="text-align:left;margin:10px 0 0;padding-left:1.25em;line-height:1.5;">'
                    + errs.map(function (e) { return '<li>' + esc(e) + '</li>'; }).join('')
                    + '</ul>',
                confirmButtonColor: color,
                width: 'min(32rem, 94vw)',
            });
        } else if (ok) {
            Swal.fire({
                icon: 'success',
                title: @json(__('jetfly.swal_success_title')),
                text: ok,
                confirmButtonColor: color,
                timer: 4800,
                timerProgressBar: true,
            });
        } else if (singleErr) {
            Swal.fire({
                icon: 'error',
                title: @json(__('jetfly.swal_error_title')),
                text: singleErr,
                confirmButtonColor: color,
            });
        }
    });
})();
</script>
