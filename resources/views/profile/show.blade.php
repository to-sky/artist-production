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
    {!! LinkMenu::make('menus.client', 'profile') !!}

    <!-- Profile -->
    {!! Form::open(array('route' => 'profile.update')) !!}
        <div class="ap_section__heading">
            <h2 class="ap_section__title">{!! __('Profile') !!}</h2>
        </div>
        <section class="ap_section ap_section--2-cols">
            <div class="ap_section__content">
                <div class="ap_form">
                    <div class="ap_form__group">
                        {!! Form::label(null, __('First name'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('profile[first_name]', old('profile[first_name]', $user->first_name), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('profile.first_name'))
                            <div class="ap_form__error">{{ $errors->first('profile.first_name') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Email'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('profile[email]', old('profile[email]', $user->email), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('profile.email'))
                            <div class="ap_form__error">{{ $errors->first('profile.email') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="ap_section__content">
                <div class="ap_form">
                    <div class="ap_form__group">
                        {!! Form::label(null, __('Last name'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('profile[last_name]', old('profile[last_name]', $user->last_name), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('other_address.last_name'))
                            <div class="ap_form__error">{{ $errors->first('profile.last_name') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Phone'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('profile[phone]', old('profile[phone]', $profile->phone), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('profile.phone'))
                            <div class="ap_form__error">{{ $errors->first('profile.phone') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="ap_section">
            <div class="ap_buttons">
                <button class="ap_button--submit">{!! __('Save') !!}</button>
            </div>
        </section>
    {!! Form::close() !!}
@endsection