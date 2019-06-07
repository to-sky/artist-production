@foreach($options as $option)
    <div class="ap_form__group ap_form__group--shipping">
        {!! Form::radio('shipping_type', $option['id'], $option['default'], [
            'class' => 'ap_form__radio-input courier_switch i_delivery',
            'id' => "ship_type_{$option['id']}",
        ]) !!}
        <label for="ship_type_{{ $option['id'] }}" class="ap_form__radio-label">{{ __($option['name']) }}</label>
        <span class="ap_form__price">{{ $option['price'] }} EUR</span>
    </div>
@endforeach