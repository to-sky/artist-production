@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.clients.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(array('route' => config('admin.route').'.clients.store')) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('first_name', __('First name')) !!}*
                        {!! Form::text('first_name', old('first_name'), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('last_name', __('Last name')) !!}*
                        {!! Form::text('last_name', old('last_name'), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('email', __('Email')) !!}*
                        {!! Form::text('email', old('email'), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('phone', __('Phone')) !!}
                        {!! Form::text('phone', old('phone'), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('commission', __('Commission')) !!}
                        {!! Form::text('commission', old('commission', App\Models\Profile::DEFAULT_COMMISSION), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('type', __('Type')) !!}
                        {!! Form::select('type', $types, old('type', App\Models\Profile::TYPE_INDIVIDUAL), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('code', __('Code')) !!}
                        {!! Form::text('code', old('code'), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('comment', __('Comment')) !!}
                        {!! Form::textarea('comment', old('comment'), array('class'=>'form-control max-width-100')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        <h4>{{ __('Address') }}</h4>
                        <div class="form-group">
                            {!! Form::label('street', __('Street')) !!}
                            {!! Form::text('street', old('street'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('apartment', __('Apartment')) !!}
                            {!! Form::text('apartment', old('apartment'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('house', __('House')) !!}
                            {!! Form::text('house', old('house'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('post_code', __('Post code')) !!}
                            {!! Form::text('post_code', old('post_code'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('city', __('City')) !!}
                            {!! Form::text('city', old('city'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('country_id', __('Country')) !!}
                            {!! Form::select('country_id', $countries, old('country_id'), array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <button type="button" id="manage-addresses" class="btn btn-primary">
                                <i class="fa fa-plus"></i> {{ __('Manage addresses') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="box-footer">

                    @include('Admin::partials.save-buttons')

                </div>

            </div>

            <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        @include('Admin::client.address')
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

        </div>

    </div>

@endsection

@section('after_styles')
    <link rel="stylesheet" href="{{ asset('/bower_components/intl-tel-input/build/css/intlTelInput.min.css') }}">

    <style>
        .modal {
            text-align: center;
            padding: 0!important;
        }

        .modal:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
            margin-right: -4px; /* Adjusts for spacing */
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }

        #address-table th, td{
            padding: 5px;
        }

        .intl-tel-input {
            display: block;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ asset('/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js') }}"></script>

    @include('Admin::partials.form-scripts')

    <script>

        var countryCodes = @json($countryCodes);

        $("#phone").intlTelInput({
            autoHideDialCode: false,
            nationalMode: false,
            preferredCountries: ['UA', 'RU', 'BY', 'US', 'UK', 'DE', 'AT'],
            initialCountry: 'DE',
            onlyCountries: countryCodes,
        });

        $(document).on('click', '#manage-addresses', function (event) {
            $('#modalForm').modal('show');
        });

        $(document).on('click', '#add-address', function(event) {
            $('#no-data').hide();

            var tmpl = $.templates("#address-row-template");
            var html =  tmpl.render({i: $('.address-row').length});

            $('#address-table').append(html);
        });

        $(document).on('click', '#remove-address', function(event) {
            $(this).parents('.address-row').remove();
            $.each($('.address-row'), function(index, item) {
                var data = {
                    first_name: $(this).find('.first-name').val(),
                    last_name: $(this).find('.last-name').val(),
                    street: $(this).find('.street').val(),
                    house: $(this).find('.house').val(),
                    apartment: $(this).find('.apartment').val(),
                    post_code: $(this).find('.post-code').val(),
                    city: $(this).find('.city').val(),
                    active: $(this).find('.active-hidden').val(),
                    i: index
                };
                var country = $(this).find('.country').val();
                var activeAddress = $(this).find('.active-address').is(':checked');
                var tmpl = $.templates("#address-row-template");
                var html = $.parseHTML(tmpl.render(data));
                $(html).find('.country').val(country);
                $(html).find('.active-address').attr('checked', activeAddress)
                $(this).replaceWith(html);
            });
            if ($('.address-row').length == 0) {
                $('#no-data').show();
            }
        });

        $(document).on('click', '.active-address', function(event){
            $('.active-hidden').val({!! \App\Models\Address::NOT_ACTIVE !!});
            $(this).parents('.address-row').find('.active-hidden').val({!! \App\Models\Address::ACTIVE !!})
        });

        $(document).on('click', '#save-address', function(event) {
            var address = $('.active-address:checked').parents('.address-row');
            if (address.length) {
                var data = {
                    street: address.find('.street').val(),
                    house: address.find('.house').val(),
                    apartment: address.find('.apartment').val(),
                    post_code: address.find('.post-code').val(),
                    city: address.find('.city').val(),
                    country: address.find('.country').val(),
                }
            }

            setAddressValues(data);

        });

        function setAddressValues(data) {
            $('#street').val(data.street);
            $('#house').val(data.house);
            $('#apartment').val(data.apartment);
            $('#post_code').val(data.post_code);
            $('#city').val(data.city);
            $('#country_id').val(data.country);
        }

    </script>

@endsection
