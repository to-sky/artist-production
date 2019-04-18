<!-- jQuery 2.2.3 -->
<script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset('/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('/bower_components/moment/locale/ru.js') }}"></script>
<script src="{{ asset('/bower_components/moment/locale/de.js') }}"></script>
<script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('/bower_components/jquery-ui/ui/minified/core.min.js') }}"></script>
<script src="{{ asset('/bower_components/jquery-ui/ui/minified/widget.min.js') }}"></script>
<script src="{{ asset('/bower_components/jquery-ui/ui/minified/mouse.min.js') }}"></script>
<script src="{{ asset('/bower_components/jquery-ui/ui/minified/sortable.min.js') }}"></script>
<script src="{{ asset('/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/pace/pace.js') }}"></script>
<script src="{{ asset('/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('/bower_components/fastclick/lib/fastclick.js') }}"></script>
<script src="{{ asset('/bower_components/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/bower_components/ckeditor/adapters/jquery.js') }}"></script>
<script src="{{ asset('/bower_components/jsrender/jsrender.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('/bower_components/select2/dist/js/select2.full.js') }}"></script>
<script src="{{ asset('/bower_components/select2/dist/js/i18n/de.js') }}"></script>
<script src="{{ asset('/bower_components/select2/dist/js/i18n/ru.js') }}"></script>
<script src="{{ asset('/bower_components/evol-colorpicker/js/evol-colorpicker.js') }}"></script>

<!-- page script -->
<script type="text/javascript">
    /* Store sidebar state */
    $('.sidebar-toggle').click(function (event) {
        event.preventDefault();
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
        } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
        }
    });
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function () {
        Pace.restart();
    });

    // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Submits logout form
    $('#logout-button').click(function(e){
        e.preventDefault();
        var form = $('#logout-form');
        form.submit();
    });

    var locale = '{{ app()->getLocale() }}';

    $('.select2-box').select2({
        language: locale,
        width: 'resolve',
        dropdownAutoWidth : true
    });

    // Adds tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initializes tabs
    $('.nav-tabs a, .nav-pills a').click(function(e) {
        e.preventDefault()
        $(this).tab('show')
    });

    /**
     * Copies text to clipboard
     * @param text
     */
    function copyToClipboard(text) {
        var textArea = document.createElement("textarea");

        // Place in top-left corner of screen regardless of scroll position.
        textArea.style.position = 'fixed';
        textArea.style.top = 0;
        textArea.style.left = 0;

        // Ensure it has a small width and height. Setting to 1px / 1em
        // doesn't work as this gives a negative w/h on some browsers.
        textArea.style.width = '2em';
        textArea.style.height = '2em';

        // We don't need padding, reducing the size if it does flash render.
        textArea.style.padding = 0;

        // Clean up any borders.
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';

        // Avoid flash of white box if rendered for any reason.
        textArea.style.background = 'transparent';

        textArea.value = text;

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }

        document.body.removeChild(textArea);
    }

    $('.datepicker').datepicker({
        format: "yyyy-mm-dd"
    });

    $('.datetimepicker').datetimepicker({
        format: 'Y-MM-D HH:mm',
        locale: locale
    });

    $('input.colorpicker').colorpicker();
</script>
