@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.events.create') }}" class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="fa fa-plus"></i>
                    {{ trans('Admin::admin.add-new', ['item' => mb_strtolower(trans('Admin::models.' . ucfirst($menuRoute->singular_name)))]) }}
                </span>
            </a>
        </div>

        <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="no-sort" width="5%" style="text-align: center">
                            {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
                        </th>
                        <th>{{ __('Event') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Time') }}</th>
                        <th>{{ __('City') }}</th>
                        <th>{{ __('Building') }}</th>
                        <th>{{ __('Hall') }}</th>
                        <th>{{ __('Active') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($events as $row)
                    <tr>
                        <td style="text-align: center">
                            {!! Form::checkbox('del-'.$row->id,1,false,['class' => 'single','data-id'=> $row->id]) !!}
                        </td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->date->toFormattedDateString() }}</td>
                        <td>{{ $row->date->format('H:i') }}</td>
                        <td>{{ $row->hall->building->city->name }}</td>
                        <td>{{ $row->hall->building->name }}</td>
                        <td>{{ $row->hall->name }}</td>
                        <td>{{ numberToString($row->is_active) }}</td>
                        <td>
                            <a href="{{ route(config('admin.route').'.events.edit', [$row->id]) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}</a>
                            <a href="{{ route(config('admin.route').'.events.destroy', [$row->id]) }}" class="btn btn-xs btn-default delete-button"><i class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="box-footer">
            <button class="btn btn-danger" id="delete">
                <span><i class="fa fa-trash"></i> {{ trans('Admin::templates.templates-view_index-delete_checked') }}</span>
            </button>
            {!! Form::open(['route' => config('admin.route').'.events.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
                <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.datatable-scripts')
@endsection
