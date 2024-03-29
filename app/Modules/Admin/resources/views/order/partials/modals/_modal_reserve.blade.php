<!-- Reserve modal -->
<div class="modal fade" id="reserveModal" tabindex="-1" role="dialog" aria-labelledby="reserveModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(array('route' => config('admin.route').'.orders.store')) !!}
            <input type="hidden" name="order_type" value="reserve">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="reserveModalLabel">{{ __('In reserve') }}</h4>
            </div>

            <div class="modal-body">
                @include('Admin::order.partials.modals._block_client_info')

                @include('Admin::order.partials._shipping_type_group')

                <div class="form-group">
                    <label for="paymentType">{{ __('Payment type') }}</label>
                    <input type="text" id="paymentType" class="form-control" value="{{ __('Bank transfer') }}" readonly>
                    <input type="hidden" name="payment_type" value="{{ \App\Models\Shipping::TYPE_POST }}">
                </div>

                <div class="form-group">
                    <label for="comment">{{ __('Comment') }}</label>
                    <textarea name="comment" id="comment" rows="4" class="form-control"></textarea>
                </div>

                @include('Admin::order.partials.modals._block_total_sum', ['shippingBlock' => true])
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('In reserve') }}</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
