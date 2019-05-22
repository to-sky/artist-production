@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.shippings.create') }}" class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label">
                    <i class="fa fa-plus"></i>
                    {{ trans('Admin::admin.add-new', ['item' => __('Admin::models.shipping')]) }}
                </span>
            </a>
        </div>

        <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th class="no-sort text-center" width="5%">
                        {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
                    </th>
                    <th>{{ __('Shipping type') }}</th>
                    <th>{{ __('Shipping zones') }}</th>
                    <th>{{ __('Default') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($shippings as $shipping)
                    <tr>
                        <td class="text-center">
                            {!! Form::checkbox('del-'.$shipping->id,1,false,['class' => 'single','data-id'=> $shipping->id]) !!}
                        </td>
                        <td>{{ $shipping->name }}</td>
                        <td>
                            @foreach($shipping->shippingZones as $shippingZone)
                                <span class="label label-default">{{ $shippingZone->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ numberToString($shipping->is_default) }}</td>

                        <td>
                            <a href="{{ route(config('admin.route').'.shippings.edit', [$shipping->id]) }}"
                               class="btn btn-xs btn-default">
                                <i class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}
                            </a>

                            {!! Form::open(['route' => ['shippings.set-default', $shipping->id], 'method' => 'patch', 'class' => 'inline']) !!}
                            <button type="submit" class="btn btn-xs btn-default inline" @if ($shipping->is_default) disabled @endif>
                                <i class="fa fa-check"></i> {{ trans('Admin::admin.set-to-default') }}
                            </button>
                            {!! Form::close() !!}

                            <a href="{{ route(config('admin.route').'.shippings.destroy', [$shipping->id]) }}"
                               class="btn btn-xs btn-default delete-button">
                                <i class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="box-footer">
            <button class="btn btn-danger" id="delete">
                <span>
                    <i class="fa fa-trash"></i>
                    {{ trans('Admin::templates.templates-view_index-delete_checked') }}
                </span>
            </button>
            {!! Form::open(['route' => config('admin.route').'.shippings.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
            <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.datatable-scripts')
@endsection
