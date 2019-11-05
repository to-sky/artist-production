@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.users.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(['route' => config('admin.route').'.users.store', 'enctype' => 'multipart/form-data']) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.add-new', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('avatar', __('Avatar')) !!}
                        {!! Form::file('avatar', [
                          'accept' => FileHelper::mimesImage(),
                        ]) !!}

                        <input type="hidden" name="MAX_FILE_SIZE" value="{{ FileHelper::maxUploadSize() }}">
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('first_name', __('First name')) !!}
                        {!! Form::text('first_name', old('first_name'), ['class'=>'form-control', 'placeholder'=> __('First name')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('last_name', __('Last name')) !!}
                        {!! Form::text('last_name', old('last_name'), ['class'=>'form-control', 'placeholder'=> __('Last name')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('email', __('Email')) !!}*
                        {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=> __('Email')]) !!}
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
                        {!! Form::label('role_id', trans('Admin::admin.users-create-role')) !!}
                        {!! Form::select('role_id', $roles, old('role_id'), ['class'=>'form-control']) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('active', __('Active')) !!}
                        <div class="radio">
                            <label>
                                {!! Form::radio('active', \App\Models\User::ACTIVE, (old('active', 1) == \App\Models\User::ACTIVE)) !!}
                                {{ __('Yes') }}
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {!! Form::radio('active', \App\Models\User::NOT_ACTIVE, (old('active', 1) == \App\Models\User::NOT_ACTIVE)) !!}
                                {{ __('No') }}
                            </label>
                        </div>
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


