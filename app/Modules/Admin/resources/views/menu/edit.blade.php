@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route') . '.menu.index') }}"><i class="fa fa-angle-double-left"></i> {{ trans('Admin::admin.back-to-all-entries') }}</a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open() !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('Admin::admin.edit-item', ['item' => trans('Admin::models.' . $menuRoute->singular_name)]) }}</h3>
                </div>

                <div class="box-body row">
                    @if($menu->menu_type != 2)
                        <div class="form-group  col-md-12">
                            {!! Form::label('parent_id', trans('Admin::qa.menus-edit-parent')) !!}
                            {!! Form::select('parent_id', $parentsSelect, old('parent_id', $menu->parent_id), ['class'=>'form-control']) !!}
                        </div>
                    @endif

                    <div class="form-group col-md-12">
                        {!! Form::label('title', trans('Admin::qa.menus-edit-title')) !!}
                        {!! Form::text('title', old('title',$menu->title), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-edit-title_placeholder')]) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('roles', trans('Admin::qa.menus-edit-roles')) !!}
                        @foreach($roles as $role)
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('roles['.$role->id.']',$role->id,old('roles.'.$role->id, $menu->roles()->where('role_id', $role->id)->pluck('id')->first())) !!}
                                    {!! __($role->display_name) !!}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('icon', trans('Admin::qa.menus-edit-icon')) !!}
                        {!! Form::text('icon', old('icon',$menu->icon), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-edit-icon_placeholder')]) !!}
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