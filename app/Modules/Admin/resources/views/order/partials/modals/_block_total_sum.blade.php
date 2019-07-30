<div class="modal-sum">
    <div class="modal-tickets">
        <h5>
            {{ __('Tickets') }}: <span data-tickets="count">0</span>&nbsp;<i class="fa fa-ticket text-muted"></i>
        </h5>
        <div data-tickets="content"></div>
    </div>

    @if(isset($shippingBlock))
    <p>
        {{ __('Shipping price') }}: <span data-tickets="shipping-price">0</span> <i class="fa fa-euro text-muted text-right"></i>
    </p>
    @endif
    <p>
        {{ __('Amount to pay') }}: <span data-tickets="final-price">0</span>&nbsp;<i class="fa fa-euro text-muted text-right"></i>
    </p>
</div>
