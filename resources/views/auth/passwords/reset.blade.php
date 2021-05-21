@extends('layouts.master')

@section('content')
    <!-- Password reset -->
    {!! Form::open(array('route' => 'password.request')) !!}
        <section class="ap_section">
            <div class="ap_section__content">
                <div class="ap_form">
                    <input type="hidden" name="token" value="{{ $token }}">

                    @if (session('status'))
                        <div class="ap_form__group">
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Admin::auth.reset-email'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('email', $email ?? old('email'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('email'))
                            <div class="ap_form__error">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Admin::auth.reset-password'), array('class'=>'ap_form__label')) !!}
                        {!! Form::password('password', array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('password'))
                            <div class="ap_form__error">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Admin::auth.reset-confirm_password'), array('class'=>'ap_form__label')) !!}
                        {!! Form::password('password_confirmation', array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('password_confirmation'))
                            <div class="ap_form__error">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="ap_section">
            <div class="ap_buttons">
                <button class="ap_button--submit ap_button--block">{!! __('Admin::auth.reset-btnreset_password') !!}</button>
            </div>
        </section>
    {!! Form::close() !!}
@endsection
