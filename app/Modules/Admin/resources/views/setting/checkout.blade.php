@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            @include('Admin::partials.errors')

            {!! Form::open(['route' => config('admin.route') . '.settings.checkoutStore']) !!}

            <div class="box">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="form-group col-md-12">
                        {!! Form::label('checkout_courier_price', __('Courier price')) !!}
                        {!! Form::text('settings[checkout_courier_price]', old('checkout_courier_price', setting('checkout_courier_price', null)), ['class'=>'form-control', 'id' => 'checkout_courier_price', 'placeholder'=> __('Courier price')]) !!}
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">
                        <span class="fa fa-save" role="presentation" aria-hidden="true"></span> <span>{{ __('Save') }}</span>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection