<script>
    jQuery(document).ready(function($) {
        var table = $('#datatable').DataTable({
            order: [],
            columnDefs: [
                {
                    targets: [],
                    orderable: false
                }
            ],
            ordering: false,
            language: {
                url: '{{ route('dataTables.locale', ['locale' => App::getLocale()]) }}'
            }
        }).on('click', '.delete-button', function(e){
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
            checkMass = false;

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

            checkMass = true;
        });

        var checkMass = true;
        $('.single').change(function() {
          if (!checkMass) return;

          setTimeout(function () {
            if (!$('.single:not(:checked)').length) {
              $('.mass').prop('checked', true);
            } else {
              $('.mass').prop('checked', false);
            }
          });
        });

        $('#delete').click(function () {
            if (confirm('{{ trans('Admin::templates.templates-view_index-are_you_sure') }}')) {
                var send = $('#send');

                if ($('.mass').is(':checked')) {
                    send.val('mass');
                } else {
                    var toDelete = [];
                    $(".single:checked").each(function() {
                        toDelete.push($(this).data('id'));
                    });
                    send.val(JSON.stringify(toDelete));
                }

                $('#massDelete').submit();
            }
        });
    });
</script>
