@extends('layouts.master')

@section('content')
    <!-- Password reset request -->
    {!! Form::open(array('route' => 'password.email')) !!}
        <section class="ap_section">
            <div class="ap_section__heading">
                <h2 class="ap_section__title">{{ trans('Admin::auth.password-reset_password') }}</h2>
            </div>

            <div class="ap_section__content">
                <div class="ap_form">
                    @if (session('status'))
                        <div class="ap_form__group">
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    <div class="ap_form__group">
                        {!! Form::label(null, __('Admin::auth.password-email'), array('class'=>'ap_form__label')) !!}
                        {!! Form::text('email', old('email'), array('class'=>'ap_form__input')) !!}
                        @if ($errors->has('email'))
                            <div class="ap_form__error">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="ap_section">
            <div class="ap_buttons">
                <button class="ap_button--submit" style="display: block; width: 100%;">{!! __('Admin::auth.password-btnsend_password') !!}</button>
            </div>
        </section>
    {!! Form::close() !!}
@endsection
