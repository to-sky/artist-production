<script>

    jQuery(document).ready(function($) {

        var table = $('#datatable').DataTable({
            order: [],
            columnDefs: [
                {
                    targets: 'no-sort',
                    orderable: false
                }
            ]
        });

        $('#datatable').on('click', '.delete-button', function(e){
            e.preventDefault();
            var $this = $(this),
                url = $(this).attr('href');

            if (confirm('{{ trans('Admin::templates.templates-view_index-are_you_sure') }}')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function (result) {
                        // Shows an alert with the result
                        new PNotify({
                            title: '{{ trans('Admin::templates.templates-view_index-item_deleted_title') }}',
                            text: '{{ trans('Admin::templates.templates-view_index-item_deleted') }}',
                            type: 'success'
                        });
                        // Deletes the row from the table
                        table
                            .row( $this.parents('tr') )
                            .remove()
                            .draw();

                        table.draw();
                    },
                    error: function (result) {
                        // Logs the error result to console
                        console.log(result);
                        // Shows an error
                        new PNotify({
                            title: '{{ trans('Admin::templates.templates-view_index-item_deleted_error_title') }}',
                            text: '{{ trans('Admin::templates.templates-view_index-item_deleted_error') }}',
                            type: 'warning'
                        });
                    }
                });
            }
        });

        $('.mass').click(function () {
            if ($(this).is(":checked")) {
                $('.single').each(function () {
                    if ($(this).is(":checked") == false) {
                        $(this).click();
                    }
                });
            } else {
                $('.single').each(function () {
                    if ($(this).is(":checked") == true) {
                        $(this).click();
                    }
                });
            }
        });

        $('#delete').click(function () {
            if (confirm('{{ trans('Admin::templates.templates-view_index-are_you_sure') }}')) {
                var send = $('#send'),
                    mass = $('.mass').is(":checked");

                if (mass == true) {
                    send.val('mass');
                } else {
                    var toDelete = [];
                    $('.single').each(function () {
                        if ($(this).is(":checked")) {
                            toDelete.push($(this).data('id'));
                        }
                    });
                    send.val(JSON.stringify(toDelete));
                }
                $('#massDelete').submit();
            }
        });
    });
</script>