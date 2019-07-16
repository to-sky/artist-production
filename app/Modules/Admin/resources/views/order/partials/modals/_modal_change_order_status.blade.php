<div class="modal fade" id="changeOrderStatus" tabindex="-1" role="dialog" aria-labelledby="changeOrderStatusLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="changeOrderStatusLabel">
                    {{ __('Change of payment status of order') }} № <span id="changeOrderStatusOrderId"></span>
                </h4>

            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="order_status">{{ __('Order status') }}</label>
                    <select name="order_status" id="orderStatus" class="form-control">
                        <option disabled selected>{{ __('Select :item', ['item' => __('status')]) }}</option>
                        <option value="{{ \App\Models\Order::STATUS_CONFIRMED }}">{{ __('Confirmed') }}</option>
                        <option value="{{ \App\Models\Order::STATUS_CANCELED }}">{{ __('Cancelled') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_method">{{ __('Payment method') }}</label>
                    <select name="payment_method" id="paymentMethod" class="form-control" disabled="disabled">
                        <option disabled selected>{{ __('Select :item', ['item' => __('payment method')]) }}</option>
                        @foreach($paymentMethods as $id => $method)
                            <option value="{{ $id ?? '' }}">{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" id="changeOrderStatusBtn" class="btn btn-primary" disabled="disabled">{{ __('Confirm') }}</button>
            </div>
        </div>
    </div>
</div>
