@extends('layouts.master')

@section('page-header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ __('Payment') }}</span>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('dashboard') }}">{{ __('Checkout') }}</a></li>
        </ol>

    </section>
@endsection

@section('content')

    @if(Cart::count())

    <!-- Tickets -->
    <section class="ap_section">
        <div class="ap_section__heading">
            <h2 class="ap_section__title">{!! __('Chosen tickets <b>:quantity</b>', ['quantity' => Cart::count()]) !!}</h2>
            <p class="ap_section__info">{!! __('Time left to place an order') !!} <span id="counter"></span></p>
        </div>

        <div class="ap_section__content ap_tickets">
            @foreach (Cart::content() as $item)
                <article class="ap_ticket">
                    <a href="" class="ap_ticket__image" style="background-image: url({{ $item->model->event->eventImage->file_url ?? asset('images/no-image.jpg') }});"></a>
                    <div class="ap_ticket__info">
                        <h3 class="ap_ticket__title">{{ $item->name }}</h3>
                        <p class="ap_ticket__price">
                            {{ $item->price }} EUR
                            @if($item->model->priceGroup)
                                ({{ $item->model->priceGroup->name }})
                            @endif
                        </p>
                        <p class="ap_ticket__detail"><i class="fas fa-calendar-alt"></i> {{ $item->model->event->date->format('d.m.Y') }}</p>
                        <p class="ap_ticket__detail"><i class="fas fa-calendar-alt"></i> {{ __($item->model->event->date->format('l')) }}</p>
                        <p class="ap_ticket__detail"><i class="fas fa-clock"></i> {{ $item->model->event->date->format('H:i') }}</p>
                        <p class="ap_ticket__detail"><i class="fas fa-map-marked-alt"></i> {{ $item->model->address }}</p>
                        <p class="ap_ticket__detail">
                            <i class="fas fa-map-marker-alt"></i>
                            @php $place = $item->model->place; @endphp
                            {!! __('Block') !!} {{ $place->zone->name ?? '-' }}:
                            {!! __('Row') !!} {{ $place->row ?? '-' }},
                            {!! __('Seat') !!} {{ $place->num ?? '-' }}
                        </p>
                    </div>
                    {!! Form::open(['route' => ['cart.remove', 'id' => $item->model->id]]) !!}
                        <button class="ap_ticket__remove"></button>
                    {!! Form::close() !!}
                </article>
            @endforeach
            <div class="ap_tickets__footer">
                <p class="ap_tickets__footer-text">{!! __('Total') !!}: <b>{{ Cart::subtotal() }} EUR</b></p>
                {!! Form::open(['route' => 'cart.destroy']) !!}
                    <button class="ap_tickets__footer-text">{{ __('Remove all tickets') }}</button>
                {!! Form::close() !!}
            </div>
        </div>
    </section>

    {!! Form::open(array('route' => 'payment.processCheckout')) !!}

        @if(auth()->check())
            <!-- Client Addresses -->
            <section class="ap_section">
                <div class="ap_section__heading">
                    <h2 class="ap_section__title">{{ __('Another addresses') }}</h2>
                </div>
                <div class="ap_form">
                    <h3 class="ap_form__radio-title">{{ __('Add address') }}</h3>

                    @foreach($addresses as $address)
                        <div class="ap_form__group">
                            <input
                                    id="adr_{{ $address->id }}"
                                    name="address_id"
                                    type="radio"
                                    class="ap_form__radio-input"
                                    @if($loop->first) checked="checked" @endif
                                    data-country="{{ $address->country_id }}"
                                    value="{{ $address->id }}"
                            >
                            <label for="adr_{{ $address->id }}" class="ap_form__radio-label">{{ $address->full }}</label>
                        </div>
                    @endforeach

                    <div class="ap_form__group">
                        <input
                                id="other_address_switch"
                                name="other_address_check"
                                type="checkbox"
                                class="ap_form__checkbox-input shipping_switch"
                                value="1"
                                @if(old('other_address_check')) checked="checked" @endif>
                        <label for="other_address_switch" class="ap_form__checkbox-label">
                            {{ __('Deliver to different address') }}
                        </label>
                    </div>
                </div>
            </section>
        @else
            <!-- Client Details -->
            <section class="ap_section ap_section--2-cols">
                <div class="ap_section__content">
                    <div class="ap_section__heading">
                        <h2 class="ap_section__title">{{ __('Contact information') }}</h2>
                    </div>
                    <div class="ap_form">
                        <div class="ap_form__group">
                            {!! Form::label('first_name', __('First name'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('user[first_name]', old('user[first_name]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('user.first_name'))
                                <div class="ap_form__error">{{ $errors->first('user.first_name') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('last_name', __('Last name'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('user[last_name]', old('user[last_name]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('user.last_name'))
                                <div class="ap_form__error">{{ $errors->first('user.last_name') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('email', __('Email'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('user[email]', old('user[email]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('user.email'))
                                <div class="ap_form__error">{{ $errors->first('user.email') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('email_confirm', __('Email Confirm'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('user[email_confirm]', old('user[email_confirm]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('user.email_confirm'))
                                <div class="ap_form__error">{{ $errors->first('user.email_confirm') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('phone', __('Phone'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('user[phone]', old('user[phone]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('user.phone'))
                                <div class="ap_form__error">{{ $errors->first('user.phone') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="ap_section__content">
                    <div class="ap_section__heading">
                        <h2 class="ap_section__title">{{ __('Address') }}</h2>
                    </div>
                    <div class="ap_form">
                        <div class="ap_form__group">
                            {!! Form::label('street', __('Street'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('billing_address[street]', old('billing_address[street]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('billing_address.street'))
                                <div class="ap_form__error">{{ $errors->first('billing_address.street') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('house', __('House'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('billing_address[house]', old('billing_address[house]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('billing_address.house'))
                                <div class="ap_form__error">{{ $errors->first('billing_address.house') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('apartment', __('Apartment'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('billing_address[apartment]', old('billing_address[apartment]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('billing_address.apartment'))
                                <div class="ap_form__error">{{ $errors->first('billing_address.apartment') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('post_code', __('Post code'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('billing_address[post_code]', old('billing_address[post_code]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('billing_address.post_code'))
                                <div class="ap_form__error">{{ $errors->first('billing_address.post_code') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('city', __('City'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('billing_address[city]', old('billing_address[city]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('billing_address.city'))
                                <div class="ap_form__error">{{ $errors->first('billing_address.city') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('country_id', __('Country'), array('class'=>'ap_form__label')) !!}
                            {!! Form::select('billing_address[country_id]', $countries, old('country_id'), array('class' => 'ap_form__input shipping_switch i_country')) !!}
                            @if ($errors->has('billing_address.country_id'))
                                <div class="ap_form__error">{{ $errors->first('billing_address.country_id') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            <input
                                    id="other_address_switch"
                                    name="other_address_check"
                                    type="checkbox"
                                    class="ap_form__checkbox-input shipping_switch"
                                    value="1"
                                    @if(old('other_address_check')) checked="checked" @endif>
                            <label for="other_address_switch" class="ap_form__checkbox-label">
                                {{ __('Deliver to different address') }}
                            </label>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Other Address Section -->
        <section class="ap_section other_address">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{{ __('Deliver to different address') }}</h2>
            </div>

            <section class="ap_section ap_section--2-cols">
                <div class="ap_section__content">
                    <div class="ap_form">
                        <div class="ap_form__group">
                            {!! Form::label(null, __('First name'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('other_address[first_name]', old('other_address[first_name]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('other_address.first_name'))
                                <div class="ap_form__error">{{ $errors->first('other_address.first_name') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('street', __('Street'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('other_address[street]', old('other_address[street]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('other_address.street'))
                                <div class="ap_form__error">{{ $errors->first('other_address.street') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('apartment', __('Apartment'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('other_address[apartment]', old('other_address[apartment]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('other_address.apartment'))
                                <div class="ap_form__error">{{ $errors->first('other_address.apartment') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('city', __('City'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('other_address[city]', old('other_address[city]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('other_address.city'))
                                <div class="ap_form__error">{{ $errors->first('other_address.city') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="ap_section__content">
                    <div class="ap_form">
                        <div class="ap_form__group">
                            {!! Form::label(null, __('Last name'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('other_address[last_name]', old('other_address[last_name]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('other_address.last_name'))
                                <div class="ap_form__error">{{ $errors->first('other_address.last_name') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('house', __('House'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('other_address[house]', old('other_address[house]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('other_address.house'))
                                <div class="ap_form__error">{{ $errors->first('other_address.house') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('post_code', __('Post code'), array('class'=>'ap_form__label')) !!}
                            {!! Form::text('other_address[post_code]', old('other_address[post_code]'), array('class'=>'ap_form__input')) !!}
                            @if ($errors->has('other_address.post_code'))
                                <div class="ap_form__error">{{ $errors->first('other_address.post_code') }}</div>
                            @endif
                        </div>
                        <div class="ap_form__group">
                            {!! Form::label('country_id', __('Country'), array('class'=>'ap_form__label')) !!}
                            {!! Form::select('other_address[country_id]', $countries, old('other_address[country_id]'), array(
                                'class' => 'ap_form__input shipping_switch i_other_country',
                            )) !!}
                            @if ($errors->has('other_address.country_id'))
                                <div class="ap_form__error">{{ $errors->first('other_address.country_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <!-- Shipping and Payment -->
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{{ __('Delivery and payment') }}</h2>
            </div>
            <div class="ap_form ap_form--2-cols">
                <div class="no-border">
                    <h3 class="ap_form__radio-title">{{ __('Delivery method') }}</h3>
                    <div class="ap_form__group ap_form__group--shipping">
                        {!! Form::radio('shipping_zone_id', '', true, [
                            'class' => 'ap_form__radio-input',
                            'id' => 'ship_type_mail',
                            'data-shipping-price' => '0',
                        ]) !!}
                        <label for="ship_type_mail" class="ap_form__radio-label">{{ __('E-ticket') }}</label>
                        <span class="ap-form__price">0.00 EUR</span>
                    </div>
                    <div class="shippings_list"></div>
                    @if ($errors->has('shipping_type'))
                        <div class="ap_form__error">{{ $errors->first('shipping_type') }}</div>
                    @endif
                </div>
                <div>
                    <h3 class="ap_form__radio-title">{{ __('Payment method') }}</h3>
                    @foreach($paymentMethods as $paymentMethod)
                        <div class="ap_form__group">
                            {!! Form::radio('payment_method_id', $paymentMethod->id, true, array(
                                'class' => 'ap_form__radio-input',
                                'id' => "payment_method_{$paymentMethod->id}",
                                'data-service-price' => $paymentMethod->calculateServicePrice(Cart::subtotal()),
                            )) !!}
                            <label for="{{ "payment_method_{$paymentMethod->id}" }}" class="ap_form__radio-label">
                                {{ $paymentMethod->name }}
                                @if($paymentMethod->display_service_price)
                                    ({{ $paymentMethod->display_service_price }})
                                @endif
                            </label>
                        </div>
                    @endforeach
                    @if ($errors->has('payment_method_id'))
                        <div class="ap_form__error">{{ $errors->first('payment_method_id') }}</div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Approvement -->
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{!! __('Confirmation') !!}</h2>
            </div>
            <div class="approvement">
                <p class="approvement__price">{!! __('Total') !!}: <b>{{ Cart::total() }} EUR</b></p>
                <div class="approvement__checkbox">
                    {!! Form::checkbox('confirmation', 1, false, array(
                        'class' => 'ap_form__checkbox-input',
                        'id' => 'confirmation',
                    )) !!}
                    <label for="confirmation" class="ap_form__checkbox-label">
                        {!! __('I have read and agree to the Terms and Conditions (AGB), the Terms of Return (Widerrufsbelehrung) and the Privacy Policy (Datenschutzerklärung).') !!}
                    </label>
                    @if ($errors->has('confirmation'))
                        <div class="ap_form__error">{{ $errors->first('confirmation') }}</div>
                    @endif
                </div>
                <button class="approvement__btn">{{ __('Checkout') }}</button>
                <a href="#" class="approvement__back">{{ __('Back') }}</a>
            </div>
        </section>

    {!! Form::close() !!}
    @else
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        {{ __('Your cart is empty') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('after_scripts')
    <script>
        $(".collapse").each(function () {
            if ($(this).hasClass('no_default')) {
                $(this).collapse('hide');
            } else {
                $(this).collapse('show');
            }
        });

        var orderSeconds = (+ new Date) + 1800000;
        var cartCount = {{ Cart::count() }};

        var cookieOrderSeconds = getCookie('orderSeconds');
        if (!cookieOrderSeconds) {
            setCookie('orderSeconds', orderSeconds, {expires: 3600});
            cookieOrderSeconds = orderSeconds;
        } else {
            cookieOrderSeconds = parseInt(cookieOrderSeconds) + 1800000;
        }

        if (cartCount) {
            var orderTimerId = setInterval(orderTimer, 500);
        } else {
            deleteCookie('orderSeconds');
        }

        function orderTimer(){
            var now = +new Date;
            var diff = Math.floor((cookieOrderSeconds - now) / 1000);
            var hours   = Math.floor(diff / 3600);
            var minutes = Math.floor((diff - (hours * 3600)) / 60);
            var seconds = diff - (hours * 3600) - (minutes * 60);

            if (minutes < 10) {
              minutes = '0' + minutes;
            }

            if (seconds < 10) {
              seconds = '0' + seconds;
            }
            $('#counter').html(minutes + ':' + seconds);

            if (minutes <= 0 && seconds <= 0) {
                deleteCookie('orderSeconds');
                clearInterval(orderTimerId);
                $.ajax({
                  url: '{{ route('cart.destroy') }}',
                  type: 'POST',
                  data: {
                   _token: '{{ csrf_token() }}'
                  },
                  dataType: 'json',
                  async: false,
                  success: function(result) {
                    window.location.href = '{{ route('payment.checkout') }}';
                  }
                });
                return;
            }
        }

        var $switchers = $('.shipping_switch');
        var $countryInput = $('.i_country');
        var $otherCountryInput = $('.i_other_country');
        var $otherAddressContriner = $('.other_address');
        var $otherAddressSwitcher = $('#other_address_switch');
        var $shippingsContainer = $('.shippings_list');

        // Shippings update
        function loadShippingOptions() {
          var countryId = getCountryId();

          $shippingsContainer.load('/payment/shippingOptions/'+countryId);
        }

        function getCountryId() {
          var id;
          if (id = $('input[name="address_id"]:checked').data('country'))
            return id;

          var countryId = $otherAddressSwitcher.prop('checked')
            ? $otherCountryInput.val()
            : $countryInput.val()
          ;

          return countryId;
        }

        loadShippingOptions();
        $switchers.change(loadShippingOptions);

        // Other address section
        function otherAddressSwitch() {
          if ($otherAddressSwitcher.prop('checked')) {
            $otherAddressContriner.show();
          } else {
            $otherAddressContriner.hide();
          }
        }

        otherAddressSwitch();
        $otherAddressSwitcher.change(otherAddressSwitch);

//        $(document).on('change', '#shippings', function(event){
//            var shipping_id = $(this).val();
//            var shipping_zone_id = $('#shipping-zones-' + shipping_id).find('select').val();
//            $('input[name="shipping_zone_id"]').val(shipping_zone_id);
//            $('.shipping-zones').hide();
//            $('#shipping-zones-' + shipping_id).show();
//        });
    </script>
@endsection
