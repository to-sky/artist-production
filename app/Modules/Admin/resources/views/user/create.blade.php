@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.users.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>users</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(['route' => config('admin.route').'.users.store']) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Add a new user</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('first_name', trans('Admin::admin.users-create-first-name')) !!}
                        {!! Form::text('first_name', old('first_name'), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-create-first-name_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('email', trans('Admin::admin.users-create-email')) !!}
                        {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-create-email_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('password', trans('Admin::admin.users-create-password')) !!}
                        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-create-password_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('role_id', trans('Admin::admin.users-create-role')) !!}
                        {!! Form::select('role_id', $roles, old('role_id'), ['class'=>'form-control']) !!}
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

@endsection


