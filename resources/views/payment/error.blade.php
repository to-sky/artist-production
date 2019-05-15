@extends('layouts.master')

@section('page-header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ __('Payment error') }}</span>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('dashboard') }}">{{ __('Error') }}</a></li>
        </ol>

    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @include('Admin::partials.errors')

            <div class="box">
                <div class="box-body row">
                    <div class="form-group col-md-12">
                        <h3 class="box-title">{{ __('Please contact administrator. Your order number is :order_number.', ['order_number' => $order->id]) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection