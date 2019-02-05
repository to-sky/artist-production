@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="box">
        <div class="box-header with-border">
            <a href="{{ route(config('admin.route').'.users.create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> Add user</span></a>
        </div>
        <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="no-sort" width="5%" style="text-align: center">
                            {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
                        </th>
                        <th>{{ trans('Admin::admin.users-index-name') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td  style="text-align: center">
                            {!! Form::checkbox('del-'.$user->id,1,false,['class' => 'single','data-id'=> $user->id]) !!}
                        </td>
                        <td>{{ $user->first_name }}</td>
                        <td>
                            <a href="{{ route(config('admin.route').'.users.edit', [$user->id]) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}</a>
                            <a href="{{ route(config('admin.route').'.users.destroy', [$user->id]) }}" class="btn btn-xs btn-default delete-button"><i class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('after_scripts')

    @include('Admin::partials.datatable-scripts')

@endsection