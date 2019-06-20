{{-- TODO: need to dynamic add tickets, count, sum --}}
<div class="modal-sum">
    <div class="modal-tickets">
        <h5>
            {{ __('Tickets') }}: <span class="ticket-count">2</span>&nbsp;<i class="fa fa-ticket text-muted"></i>
        </h5>

        <input type="hidden" name="tickets[]" value="1">
        <input type="hidden" name="tickets[]" value="2">
    </div>

    <h5>
        {{ __('Amount to pay') }}: <span class="final-price">130</span>&nbsp;<i class="fa fa-euro text-muted text-right"></i>
    </h5>
</div>
