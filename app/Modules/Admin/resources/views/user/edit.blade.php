@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.users.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(['route' => [config('admin.route').'.users.update', $user->id], 'method' => 'PATCH']) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.edit-item', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('first_name', __('First name')) !!}
                        {!! Form::text('first_name', old('first_name', $user->first_name), ['class'=>'form-control', 'placeholder'=> __('First name')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('last_name', __('Last name')) !!}
                        {!! Form::text('last_name', old('last_name', $user->last_name), ['class'=>'form-control', 'placeholder'=> __('Last name')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('email', __('Email')) !!}*
                        {!! Form::email('email', old('email', $user->email), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-edit-email_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('password', __('Password')) !!}
                        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> __('Password')]) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('password_confirmation', __('Confirm Password')) !!}
                        {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=> __('Confirm Password')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('role_id', trans('Admin::admin.users-edit-role')) !!}
                        {!! Form::select('role_id', $roles, old('role_id', $user->roles()->pluck('id')->first()), ['class'=>'form-control']) !!}
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


