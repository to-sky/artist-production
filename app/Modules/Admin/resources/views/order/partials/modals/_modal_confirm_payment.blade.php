<div class="modal fade" id="confirmPayment" tabindex="-1" role="dialog" aria-labelledby="confirmPaymentLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="confirmPaymentLabel">
                    {{ __('Proof of payment') }}
                </h4>

            </div>

            <div class="modal-body">
                <p>{{ __('Confirm payment for this order?') }}</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" id="confirmPaymentBtn" class="btn btn-primary">{{ __('Confirm') }}</button>
            </div>
        </div>
    </div>
</div>
