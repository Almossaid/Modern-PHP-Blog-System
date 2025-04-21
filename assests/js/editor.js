// Wait for DOM content to be loaded before initializing TinyMCE
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE Editor
    tinymce.init({
    selector: '#content',
    plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste wordcount emoticons',
    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media | removeformat code preview',
    height: 500,
    menubar: true,
    branding: false,
    promotion: false,
    relative_urls: false,
    remove_script_host: false,
    convert_urls: true,
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 16px; line-height: 1.6; }',
    style_formats: [
        { title: 'Headings', items: [
            { title: 'Heading 2', format: 'h2' },
            { title: 'Heading 3', format: 'h3' },
            { title: 'Heading 4', format: 'h4' }
        ]},
        { title: 'Inline', items: [
            { title: 'Bold', format: 'bold' },
            { title: 'Italic', format: 'italic' },
            { title: 'Underline', format: 'underline' },
            { title: 'Code', format: 'code' }
        ]}
    ],
    setup: function(editor) {
        editor.on('change', function() {
            editor.save();
        });
    }
    });
});