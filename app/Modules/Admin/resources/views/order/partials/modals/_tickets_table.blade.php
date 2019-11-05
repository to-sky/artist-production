<div class="box">
    <div>
        <table class="table table-striped">
            <tr>
                <td>{{ __('Ticket') }}</td>
                <td>{{ __('Price') }}</td>
                <td>{{ __('Discount') }}</td>
                <td>{{ __('Bonus') }}</td>
                <td>{{ __('Cash collection') }}</td>
                <td>{{ __('Final price') }}</td>
                <td>{{ __('Admin::admin.delete') }}</td>
            </tr>
            <tr>
                <td colspan="7" class="text-center">
                    <small>{{ __(':items not selected', ['items' => __('Tickets')]) }}</small>
                </td>
            </tr>
        </table>
    </div>