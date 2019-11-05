<script>
    jQuery('document').ready(function ($) {

        // Save button has multiple redirects: save and exit, save and edit, save and new
        var saveButtons = $('#save-buttons'),
            form = saveButtons.parents('form'),
            redirectField = $('[name="save_redirect"]');

        saveButtons.on('click', '.dropdown-menu a', function () {
            redirectField.val($(this).data('redirect'));
            form.submit();
        });

        // Ctrl+S and Cmd+S trigger Save button click
        $(document).keydown(function (e) {
            if ((e.which == '115' || e.which == '83' ) && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                $("button[type=submit]").trigger('click');
                return false;
            }
            return true;
        });

        // Place the focus on the first element in the form
        @if(config('admin.focusFirstField'))
            var focusField = $('form').find('input, textarea, select').not('[type="hidden"]').eq(0);
                if (! focusField.lenght) {
                    return false;
                }

                fieldOffset = focusField.offset().top,
                scrollTolerance = $(window).height() / 2;

            focusField.trigger('focus');

            if (fieldOffset > scrollTolerance) {
                $('html, body').animate({scrollTop: (fieldOffset - 30)});
            }
        @endif

        // Add inline errors to the DOM
        @if (config('admin.inlineErrorsEnabled') && $errors->any())

            window.errors = {!! json_encode($errors->messages()) !!};

            $.each(errors, function (property, messages) {

                var normalizedProperty = property.split('.').map(function (item, index) {
                        return index === 0 ? item : '[' + item + ']';
                    }).join(''),
                    field = $('[name="' + normalizedProperty + '[]"]').length ? $('[name="' + normalizedProperty + '[]"]') : $('[name="' + normalizedProperty + '"]'),
                    container = field.parents('.form-group');

                container.addClass('has-error');

                $.each(messages, function (key, msg) {
                    // highlight the input that errored
                    var row = $('<div class="help-block">' + msg + '</div>');
                    row.appendTo(container);
                });
            });

        @endif


        $('.ckeditor-classic').ckeditor({
            language: '{{ app()->getLocale() }}'
        });

    });
</script>
