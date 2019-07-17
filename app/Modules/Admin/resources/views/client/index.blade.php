@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="box">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.clients.create') }}" class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</span>
            </a>
            <a href="{{ route(config('admin.route').'.clients.excel') }}" class="btn btn-success">
                <span><i class="fa fa-file-excel-o"></i> {{ __('Export') }}</span>
            </a>
        </div>

        <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th class="no-sort" width="5%" style="text-align: center">
                        {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
                    </th>
                    <th>#</th>
                    <th>{{ __('Full name') }}</th>
                    <th>{{ __('Commission %') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Comment') }}</th>

                    <th>&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($clients as $row)
                    <tr>
                        <td style="text-align: center">
                            {!! Form::checkbox('del-'.$row->id,1,false,['class' => 'single','data-id'=> $row->id]) !!}
                        </td>
                        <td>{{ $row->display_id }}</td>
                        <td>{{ $row->fullname }}</td>
                        <td>{{ $row->profile->commission }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->profile->phone }}</td>
                        <td>{{ $row->profile->type_label }}</td>
                        <td>{{ $row->profile->comment }}</td>

                        <td>
                            <a href="{{ route(config('admin.route').'.clients.edit', [$row->id]) }}"
                               class="btn btn-xs btn-default"><i
                                        class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}</a>
                            <a href="{{ route(config('admin.route').'.clients.destroy', [$row->id]) }}"
                               class="btn btn-xs btn-default delete-button"><i
                                        class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}</a>
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
            {!! Form::open(['route' => config('admin.route').'.clients.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
            <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('after_scripts')
    @include('Admin::partials.datatable-scripts')
@endsection
