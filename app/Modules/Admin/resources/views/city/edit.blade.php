@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.cities.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>{{ $menu->plural_name }}</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::model($city, array('method' => 'PATCH', 'route' => array(config('admin.route').'.cities.update', $city->id))) !!}

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Edit {{ $menu->singular_name }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
    {!! Form::label('name', 'Name*') !!}
    {!! Form::text('name', old('$name'), array('class'=>'form-control')) !!}
    
</div><div class="form-group col-md-12">
    {!! Form::label('countries_id', 'Name*') !!}
    {!! Form::select('countries_id', $countries, old('countries_id',$city->countries_id), array('class'=>'form-control')) !!}
    
</div>
                </div>

                <div class="box-footer">

                    @include('Admin::partials.save-buttons')

                    {!! Form::hidden('id', $city->id) !!}

                </div>

            </div>

            {!! Form::close() !!}

        </div>

    </div>

@endsection

@section('after_scripts')

    @include('Admin::partials.form-scripts')

@endsection