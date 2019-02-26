@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <a href="{{ route(config('admin.route').'.events.index') }}"><i class="fa fa-angle-double-left"></i> Back to all <span>{{ $menu->plural_name }}</span></a><br><br>

            @include('Admin::partials.errors')

            {!! Form::open(array('route' => config('admin.route').'.events.store')) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Add a new {{ $menu->singular_name }}</h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('name', 'Name*') !!}
                        {!! Form::text('name', old('name'), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('date', 'Date*') !!}
                        {!! Form::text('date', old('date'), array('class'=>'form-control datetimepicker')) !!}
                        <p class="help-block">Select date and time event</p>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('city_id', 'City*') !!}
                        {!! Form::select('city_id', $cities, old('city_id'), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('building_id', 'Building*') !!}
                        {!! Form::select('building_id', $buildings, old('building_id'), array('class'=>'form-control', 'disabled')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('hall_id', 'Hall*') !!}
                        {!! Form::select('hall_id', $halls, old('hall_id'), array('class'=>'form-control', 'disabled')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('ticket_refund_period', 'Ticket refund period') !!}
                        {!! Form::text('ticket_refund_period', old('ticket_refund_period'), array('class'=>'form-control')) !!}
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

    <script>
        // $('#city_id').select2();
    </script>
@endsection
