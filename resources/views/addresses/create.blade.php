@extends('layouts.master')

@section('page-header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ __('Profile') }}</span>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('profile.show') }}">{{ __('Profile') }}</a></li>
        </ol>

    </section>
@endsection

@section('content')
    {!! LinkMenu::make('menus.client', 'addresses') !!}

    <!-- Address -->
    {!! Form::open(array('route' => 'address.save')) !!}
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{!! __('Add address') !!}</h2>
            </div>
            <div class="ap_section__content">
                <div class="ap_form">
                    <div class="ap_form__group">
                        {!! Form::label(null, __('First name'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('address[first_name]', old('address[first_name]'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('address.first_name'))
                            <div class="ap_form__error">{{ $errors->first('address.first_name') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Last name'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('address[last_name]', old('address[last_name]'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('address.last_name'))
                            <div class="ap_form__error">{{ $errors->first('address.last_name') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label('country_id', __('Country'), array('class'=>'ap_form__label')) !!}
                        {!! Form::select('address[country_id]', $countries, old('address[country_id]'), array(
                            'class' => 'ap_form__input',
                        )) !!}
                        @if ($errors->has('address.country_id'))
                            <div class="ap_form__error">{{ $errors->first('address.country_id') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('City'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('address[city]', old('address[city]'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('address.city'))
                            <div class="ap_form__error">{{ $errors->first('address.city') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Post code'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('address[post_code]', old('address[post_code]'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('address.post_code'))
                            <div class="ap_form__error">{{ $errors->first('address.post_code') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Street'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('address[street]', old('address[street]'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('address.street'))
                            <div class="ap_form__error">{{ $errors->first('address.street') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('House'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('address[house]', old('address[house]'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('address.house'))
                            <div class="ap_form__error">{{ $errors->first('address.house') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Apartment'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('address[apartment]', old('address[apartment]'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('address.apartment'))
                            <div class="ap_form__error">{{ $errors->first('address.apartment') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="ap_section">
            <div class="ap_buttons">
                <button type="button" class="ap_button b_back">{!! __('Back') !!}</button>
                <button class="ap_button--submit">{!! __('Save') !!}</button>
            </div>
        </section>
    {!! Form::close() !!}
@endsection