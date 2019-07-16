<div class="modal fade" id="changeShippingStatus" tabindex="-1" role="dialog" aria-labelledby="changeShippingStatusLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="changeShippingStatusLabel">{{ __('Change in shipping status of order') }} № <span id="changeShippingStatusOrderId"></span></h4>

            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="shipping_status">{{ __('Shipping status') }}</label>
                    <select name="shipping_status" id="shippingStatus" class="form-control">
                        <option value="" disabled selected>{{ __('Select :item', ['item' => __('status')]) }}</option>
                        <option value="{{ \App\Models\Shipping::STATUS_NOT_SET }}">{{ __('Not set') }}</option>
                        <option value="{{ \App\Models\Shipping::STATUS_IN_PROCESSING }}">{{ __('In processing') }}</option>
                        <option value="{{ \App\Models\Shipping::STATUS_DISPATCHED }}">{{ __('Dispatched') }}</option>
                        <option value="{{ \App\Models\Shipping::STATUS_DELIVERED }}">{{ __('Delivered') }}</option>
                        <option value="{{ \App\Models\Shipping::STATUS_NOT_DELIVERED }}">{{ __('Not delivered') }}</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" id="shippingStatusBtn" class="btn btn-primary">{{ __('Confirm') }}</button>
            </div>
        </div>
    </div>
</div>
