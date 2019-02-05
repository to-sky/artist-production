<!-- jQuery 2.2.3 -->
<script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/pace/pace.js') }}"></script>
<script src="{{ asset('/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('/bower_components/fastclick/lib/fastclick.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>

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
        var form = $(this).parents('form');
        form.submit();
    })
    
</script>