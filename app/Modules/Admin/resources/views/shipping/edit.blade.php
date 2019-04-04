@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    @include('Admin::shipping._modal-delete-shipping-zone')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.shippings.index') }}">
                <i class="fa fa-angle-double-left"></i>
                {{ trans('Admin::admin.back-to-all-entries') }}
            </a>
            <br><br>

            @include('Admin::partials.errors')

            {!! Form::model($shipping, array('method' => 'PATCH', 'route' => array(config('admin.route').'.shippings.update', $shipping->id))) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('Admin::admin.edit-item', ['item' => __('Admin::models.shipping')]) }}
                    </h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('name', __('Shipping type')) !!}*
                        {!! Form::text('name', old('name', $shipping->name), array('class'=>'form-control')) !!}
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

                                @foreach($shipping->shipping_zones as $shipping_zone)
                                    <tr class="shipping-zone-row">
                                        <td>
                                            <input name="shipping_zones[{{ $loop->iteration }}][name]"
                                                   type="text"
                                                   class="form-control"
                                                   value="{{ $shipping_zone->name }}">
                                        </td>
                                        <td>
                                            <input name="shipping_zones[{{ $loop->iteration }}][price]"
                                                   type="number" min="0" class="form-control"
                                                   value="{{ $shipping_zone->price }}">
                                        </td>
                                        <td>
                                            <select name="shipping_zones[{{ $loop->iteration }}][countries_id][]"
                                                    class="form-control select2-box" multiple>
                                                @foreach($countries as  $id => $name)
                                                    <option value="{{ $id }}"
                                                            @if(in_array($id, $shipping_zone->getCountryIds()))
                                                                selected
                                                            @endif
                                                    >{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-xs"
                                                    data-toggle="modal"
                                                    data-target="#deleteShippingZone"
                                                    data-url="{{ route('shippings.delete-shipping-zone', ['id' => $shipping_zone->id]) }}"
                                            >
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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
