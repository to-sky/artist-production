@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

        <div class="box">

            <div class="box-header with-border">
                <a href="{{ route(config('admin.route').'.roles.create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</span></a>
            </div>

            <div class="box-body">
                <table id="datatable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('Admin::admin.roles-index-title') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ __($role->display_name) }}</td>
                                <td>
                                    <a href="{{ route(config('admin.route').'.roles.edit', [$role->id]) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('Admin::admin.users-index-edit') }}</a>
                                    <a href="{{ route(config('admin.route').'.roles.destroy', [$role->id]) }}" class="btn btn-xs btn-default delete-button"><i class="fa fa-trash"></i> {{ trans('Admin::admin.users-index-delete') }}</a>
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

