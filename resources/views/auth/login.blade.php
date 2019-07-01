@extends('layouts.master')

@section('content')
    <!-- Login -->
    {!! Form::open(array('route' => 'login')) !!}
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{{ trans('Admin::auth.login-login') }}</h2>
            </div>

            <div class="ap_section__content">
                <div class="ap_form">
                    <div class="ap_form__group">
                        {!! Form::label(null, __('Admin::auth.login-email'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('email', old('email'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('email'))
                            <div class="ap_form__error">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Admin::auth.login-password'), array('class'=>'ap_form__label')) !!}
                        {!! Form::password('password', array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('password'))
                            <div class="ap_form__error">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> {{ trans('Admin::auth.login-remember_me') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ap_section">
            <div class="ap_buttons">
                <a class="btn btn-link" href="{{ route('password.request') }}">{{ trans('Admin::auth.login-forgot_your_password') }}</a>
                <button class="ap_button--submit">{!! __('Admin::auth.login-btnlogin') !!}</button>
            </div>
        </section>
    {!! Form::close() !!}
@endsection
