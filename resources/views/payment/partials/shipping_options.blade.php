@foreach($options as $option)
    <div class="ap_form__group ap_form__group--shipping">
        {!! Form::radio('shipping_zone_id', $option['id'], $option['default'], [
            'class' => 'ap_form__radio-input courier_switch i_delivery',
            'id' => "ship_type_{$option['id']}",
            'data-shipping-price' => $option['price'],
        ]) !!}
        <label for="ship_type_{{ $option['id'] }}" class="ap_form__radio-label">{{ __($option['name']) }}</label>
        <span class="ap_form__price">{{ sprintf('%1.2f', $option['price']) }} EUR</span>
    </div>
@endforeach