@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route') . '.menu.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>menu</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(['route' => config('admin.route') . '.menu.store']) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Add a new menu</h3>
                </div>

                <div class="box-body row">

                    <div class="form-group col-md-12">
                        {!! Form::label('parent_id', 'Parent') !!}
                        {!! Form::select('parent_id', $parentsSelect, old('parent_id'), ['class'=>'form-control']) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('singular_name', 'Singular name') !!}
                        {!! Form::text('singular_name', old('singular_name'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-crud_singular_name_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('plural_name', 'Plural name') !!}
                        {!! Form::text('plural_name', old('plural_name'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-crud_plural_name_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-crud_title_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('roles', 'Roles') !!}
                        @foreach($roles as $role)
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('roles['.$role->id.']',$role->id,old('roles.'.$role->id)) !!}
                                    {!! $role->display_name !!}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('icon', 'Icon') !!}
                        {!! Form::text('icon', old('icon','fa-database'), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-createCrud-icon_placeholder')]) !!}
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

@stop