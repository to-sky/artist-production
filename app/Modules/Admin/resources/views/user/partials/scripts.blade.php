<script>
    $('#remove-avatar').click(function (e) {
        e.preventDefault();
        var self = $(this)
        if (confirm('{{ __('Are you sure you want to remove avatar?')  }}')) {
            $.ajax({
                url: self.attr('href'),
                dataType: 'json',
                success: function (response) {
                    // Shows an alert with the result
                    new PNotify({
                        title: '{{ __('Avatar removed') }}',
                        text: '{{ __('The user avatar was removed') }}',
                        type: 'success'
                    });

                    $('#user-avatar').remove();
                },
                error: function (response) {
                    // Logs the error response to console
                    console.log(response);
                    // Shows an error
                    new PNotify({
                        title: '{{ __('Remove avatar error') }}',
                        text: response.responseJSON.message,
                        type: 'warning'
                    });
                }
            });
        }
    });
</script>