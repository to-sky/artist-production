@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.roles.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>roles</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(['route' => [config('admin.route').'.roles.update', $role->id], 'method' => 'PATCH']) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Edit role</h3>
                </div>

                <div class="box-body row">

                    <div class="form-group col-md-12">
                        {!! Form::label('name', trans('Admin::admin.roles-edit-name')) !!}
                        {!! Form::text('name', old('name', $role->name), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.roles-edit-name_placeholder')]) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('display_name', trans('Admin::admin.roles-edit-display-name')) !!}
                        {!! Form::text('display_name', old('display_name', $role->display_name), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.roles-edit-display-name_placeholder')]) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('description', trans('Admin::admin.roles-edit-description')) !!}
                        {!! Form::text('description', old('description', $role->description), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.roles-edit-description_placeholder')]) !!}
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


