<!-- Sale modal -->
<div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            {!! Form::open(array('route' => config('admin.route').'.orders.store')) !!}
            <input type="hidden" name="order_type" value="sale">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="saleModalLabel">{{ __('Sale') }}</h4>
            </div>

            <div class="modal-body">
                @include('Admin::order.partials.modals._block_client_info')
                @include('Admin::order.partials.modals._block_total_sum')
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Sale') }}</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
