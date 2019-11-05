<div class="form-group">
    <label for="shippingType">{{ __('Shipping type') }}</label>
    <select name="shipping_type" id="shippingType" class="form-control">
        <option
                value="{{ \App\Models\Shipping::TYPE_EMAIL }}"
                {{ isset($order) && $order->shipping_type == \App\Models\Shipping::TYPE_EMAIL ? "selected=selected" : "" }}>
            {{ __('E-ticket') }}</option>
        <option
                value="{{ \App\Models\Shipping::TYPE_OFFICE }}"
                {{ isset($order) && $order->shipping_type == \App\Models\Shipping::TYPE_OFFICE ? "selected=selected" : "" }}>
            {{ __('Evening ticket office') }}
        </option>
        <option
                value="{{ \App\Models\Shipping::TYPE_POST }}"
                {{ isset($order) && $order->shipping_type == \App\Models\Shipping::TYPE_POST ? "selected=selected" : "" }}>
            {{ __('Post delivery') }}
        </option>
    </select>
</div>
