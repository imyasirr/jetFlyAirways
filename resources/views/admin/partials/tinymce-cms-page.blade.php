@push('styles')
<style>
    .cms-editor-wrap .tox-tinymce {
        border-radius: 10px !important;
        border: 1px solid #c9d5ef !important;
    }
    .cms-editor-hint { font-size: 12px; color: var(--admin-muted); margin-top: 6px; line-height: 1.45; max-width: 62ch; }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.4/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(function () {
    var el = document.getElementById('cms_page_body');
    if (!el || typeof tinymce === 'undefined') return;

    var jetflyContentCss = 'body{background:#f8fafc;color:#0f172a;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;font-size:16px;line-height:1.55;padding:14px 18px;margin:0;}' +
        'h1,h2,h3,h4{color:#0b2f71;font-weight:800;line-height:1.2;margin:0.6em 0 0.35em;}' +
        'h1{font-size:1.75rem;}h2{font-size:1.35rem;}h3{font-size:1.15rem;}h4{font-size:1.05rem;}' +
        'p{margin:0 0 0.85em;}ul,ol{margin:0 0 0.85em;padding-left:1.35em;}' +
        'a{color:#0b2f71;text-decoration:underline;}blockquote{margin:0.75em 0;padding-left:1em;border-left:4px solid #38bdf8;color:#334155;}' +
        'table{border-collapse:collapse;width:100%;margin:0.75em 0;}th,td{border:1px solid #e2e8f0;padding:8px 10px;text-align:left;}' +
        'th{background:#e2e8f0;color:#0b2f71;font-weight:700;}img,video{max-width:100%;height:auto;}code,pre{font-family:ui-monospace,monospace;font-size:0.9em;background:#f1f5f9;padding:2px 6px;border-radius:4px;}pre{padding:12px;overflow:auto;}';

    tinymce.init({
        selector: '#cms_page_body',
        height: 520,
        min_height: 360,
        menubar: 'edit view insert format table tools',
        base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6.8.4',
        suffix: '.min',
        plugins: 'lists link image table code fullscreen paste help wordcount visualblocks',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | removeformat | code fullscreen | help',
        toolbar_mode: 'sliding',
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Preformatted=pre',
        branding: false,
        promotion: false,
        browser_spellcheck: true,
        resize: true,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        paste_merge_formats: true,
        paste_data_images: true,
        content_style: jetflyContentCss,
        setup: function (editor) {
            editor.on('change input undo redo', function () {
                editor.save();
            });
        },
    });
})();
</script>
@endpush
