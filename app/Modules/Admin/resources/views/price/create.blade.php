@extends('Admin::layouts.master')

@section('page-header')

    @include('Admin::partials.page-header')

@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.prices.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>{{ $menu->plural_name }}</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(array('route' => config('admin.route').'.prices.store')) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Add a new {{ $menu->singular_name }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
    {!! Form::label('type', 'Type*') !!}
    {!! Form::text('type', old('$type'), array('class'=>'form-control')) !!}

</div><div class="form-group col-md-12">
    {!! Form::label('price', 'Price*') !!}
    {!! Form::text('price', old('price'), array('class'=>'form-control')) !!}

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
