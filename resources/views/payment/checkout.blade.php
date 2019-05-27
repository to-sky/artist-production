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

    {!! Form::open(array('route' => 'payment.processCheckout')) !!}

        <!-- Tickets -->
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{!! __('Chosen tickets <b>:quantity</b>', ['quantity' => Cart::count()]) !!}</h2>
                <p class="ap_section__info">Время на оформление заказа <span id="counter"></span></p>
            </div>

            <div class="ap_section__content ap_tickets">
                @foreach (Cart::content() as $item)
                    @php
                        //dd($item);
                    @endphp
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
                                Block {{ $place->zone->name ?? '-' }}:
                                Reihe {{ $place->row ?? '-' }},
                                Platz {{ $place->num ?? '-' }}
                            </p>
                        </div>
                        <a href="{{ route('cart.remove', ['place' => $item->model->id]) }}" class="ap_ticket__remove"></a>
                    </article>
                @endforeach
                <div class="ap_tickets__footer">
                    <p class="ap_tickets__footer-text">Сумма заказа: <b>{{ Cart::subtotal() }} EUR</b></p>
                    <a href="{{ route('cart.destroy') }}" class="ap_tickets__footer-text">Удалить все билеты</a>
                </div>
            </div>
        </section>

        <!-- Client Details -->
        <section class="ap_section ap_section--2-cols">
            <div class="ap_section__content">
                <div class="ap_section__heading">
                    <h2 class="ap_section__title">{{ __('Contact information') }}</h2>
                </div>
                {{--<h1>{{ __('Customer') }}</h1>--}}
                <div class="ap_form">
                    <div class="ap_form__group">
                        {!! Form::label('first_name', __('First name'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('billing_address[first_name]', old('billing_address[first_name]'), array('class'=>'ap_form__input')) !!}
                    </div>
                    <div class="ap_form__group">
                        {!! Form::label('last_name', __('Last name'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('billing_address[last_name]', old('billing_address[last_name]'), array('class'=>'ap_form__input')) !!}
                    </div>
                    <div class="ap_form__group">
                        {!! Form::label('email', __('Email'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('email', old('billing_address[email]'), array('class'=>'ap_form__input')) !!}
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
                    </div>
                    <div class="ap_form__group">
                        {!! Form::label('apartment', __('Apartment'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('billing_address[apartment]', old('billing_address[apartment]'), array('class'=>'ap_form__input')) !!}
                    </div>
                    <div class="ap_form__group">
                        {!! Form::label('house', __('House'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('billing_address[house]', old('billing_address[house]'), array('class'=>'ap_form__input')) !!}
                    </div>
                    <div class="ap_form__group">
                        {!! Form::label('post_code', __('Post code'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('billing_address[post_code]', old('billing_address[post_code]'), array('class'=>'ap_form__input')) !!}
                    </div>
                    <div class="ap_form__group">
                        {!! Form::label('city', __('City'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('billing_address[city]', old('billing_address[city]'), array('class'=>'ap_form__input')) !!}
                    </div>
                    <div class="ap_form__group">
                        {!! Form::label('country_id', __('Country'), array('class'=>'ap_form__label')) !!}
                        {!! Form::select('billing_address[country_id]', $countries, old('country_id'), array('class' => 'ap_form__input')) !!}
                    </div>
                    <div class="ap_form__group">
                        <input id="otherAddressField" type="checkbox" class="ap_form__checkbox-input">
                        <label for="otherAddressField" class="ap_form__checkbox-label">Доставка по иному адресу</label>
                    </div>
                </div>
            </div>
        </section>

        <!-- Shipping and Payment -->
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{{ __('Delivery and payment') }}</h2>
            </div>
            <div class="ap_form ap_form--2-cols">
                <div class="no-border">
                    <h3 class="ap_form__radio-title">{{ __('Delivery method') }}</h3>
                    <div class="ap_form__group">
                        {!! Form::radio('shipping_type', \App\Models\Shipping::TYPE_EMAIL, true, array('class' => 'ap_form__radio-input')) !!}
                        <label for="shippingMethodField1" class="ap_form__radio-label">{{ __('E-ticket') }}</label>
                    </div>
                    @foreach($shippings as $shipping)
                        <div class="ap_form__group">
                            {!! Form::radio('shipping_type', \App\Models\Shipping::TYPE_DELIVERY, true, array('class' => 'ap_form__radio-input')) !!}
                            <label for="shippingMethodField2" class="ap_form__radio-label">{{ __($shipping->name) }}</label>
                        </div>
                    @endforeach
                    <div class="ap_form__group">
                        {!! Form::checkbox('courier', 1, false, array('class' => 'ap_form__checkbox-input', 'id' => 'courier')) !!}
                        <label for="courier" class="ap_form__checkbox-label">{{ __('Courier') }}</label>
                        {!! Form::hidden('courier','') !!}
                    </div>
                </div>
                <div>
                    <h3 class="ap_form__radio-title">{{ __('Payment method') }}</h3>
                    @foreach($paymentMethods as $paymentMethod)
                        <div class="ap_form__group">
                            {!! Form::radio('payment_method_id', $paymentMethod->id, true, array('class' => 'ap_form__radio-input')) !!}
                            <label for="paymentMethodField1" class="ap_form__radio-label">{{ $paymentMethod->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Approvement -->
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">Подтверждение</h2>
            </div>
            <div class="approvement">
                <p class="approvement__price">Сумма заказа: <b>{{ Cart::total() }} EUR</b></p>
                <div class="approvement__checkbox">
                    {!! Form::checkbox('confirmation', 1, false, array('class' => 'ap_form__checkbox-input')) !!}
                    <input id="approvementField" type="checkbox" class="ap_form__checkbox-input">
                    <label for="approvementField" class="ap_form__checkbox-label">Я прочитал и согласен с Условиями (AGB), Условиями  возврата (Widerrufsbelehrung) и Политикой конфедициальности (Datenschutzerklärung).</label>
                </div>
                <button class="approvement__btn">{{ __('Checkout') }}</button>
                <a href="#" class="approvement__back">Назад</a>
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

        var orderSeconds = 1800;
        var cartCount = {{ Cart::count() }};

        var cookieOrderSeconds = getCookie('orderSeconds');
        if (!cookieOrderSeconds) {
            console.log(cookieOrderSeconds);
            setCookie('orderSeconds', orderSeconds, {expires: 3600});
        }

        if (cartCount) {
            var orderTimerId = setInterval(orderTimer, 1000);
        } else {
            deleteCookie('orderSeconds');
        }

        function orderTimer(){
            cookieOrderSeconds = getCookie('orderSeconds');
            console.log(cookieOrderSeconds);
            if (!cookieOrderSeconds) {
                clearInterval(orderTimerId);
                $.ajax({
                    url: '{{ route('cart.destroy') }}',
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    success: function(result) {
                    }
                });
                window.location = '{{ route('payment.checkout') }}';
                return;

            }
            orderSeconds = cookieOrderSeconds - 1;
            setCookie('orderSeconds', orderSeconds, {expires: 3600});
            var hours   = Math.floor(orderSeconds / 3600);
            var minutes = Math.floor((orderSeconds - (hours * 3600)) / 60);
            var seconds = orderSeconds - (hours * 3600) - (minutes * 60);

            if (minutes == 0 && seconds == 0) {
                deleteCookie('orderSeconds');
            }

            if (minutes < 10) {
                minutes = '0' + minutes;
            }

            if (seconds < 10) {
                seconds = '0' + seconds;
            }
            $('#counter').html(minutes + ':' + seconds);
        }

//        $(document).on('change', '#shippings', function(event){
//            var shipping_id = $(this).val();
//            var shipping_zone_id = $('#shipping-zones-' + shipping_id).find('select').val();
//            $('input[name="shipping_zone_id"]').val(shipping_zone_id);
//            $('.shipping-zones').hide();
//            $('#shipping-zones-' + shipping_id).show();
//        });
    </script>
@endsection