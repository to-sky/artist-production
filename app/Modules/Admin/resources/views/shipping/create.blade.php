@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.shippings.index') }}">
                <i class="fa fa-angle-double-left"></i>
                {{ trans('Admin::admin.back-to-all-entries') }}
            </a>
            <br><br>

            @include('Admin::partials.errors')

            {!! Form::open(array('route' => config('admin.route').'.shippings.store')) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('Admin::admin.add-new', ['item' =>  __('Admin::models.shipping')]) }}
                    </h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('name', __('Shipping type')) !!}*
                        {!! Form::text('name', old('name'), array('class'=>'form-control')) !!}
                    </div>

                    <div class="form-group col-md-12">
                        <p>
                            <b>{{ __('Shipping zones') }}</b>
                        </p>

                        <table id="shippingZoneTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <td width="20%">{{ __('Title') }}</td>
                                <td width="10%">{{ __('Price') }}</td>
                                <td>{{ __('Countries') }}</td>
                                <td  width="5%">
                                    <button type="button" id="addRow" class="btn btn-success btn-xs center-block">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            </thead>

                            <tbody>
                                @include('Admin::shipping._shipping-zone-table-row')
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="box-footer">
                    @include('Admin::partials.save-buttons')
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')
    @include('Admin::shipping._scripts')
@endsection
