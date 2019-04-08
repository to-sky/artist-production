@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.users.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(['route' => [config('admin.route').'.users.update', $user->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data']) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.edit-item', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</h3>
                </div>

                <div class="box-body row">
                    @include('Admin::user.partials.fields')
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
    @include('Admin::user.partials.scripts')

@endsection


