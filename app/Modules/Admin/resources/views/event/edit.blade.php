@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <a href="{{ route(config('admin.route').'.events.index') }}">
                <i class="fa fa-angle-double-left"></i>
                {{ trans('Admin::admin.back-to-all-entries') }}
            </a>
            <br><br>

            @include('Admin::partials.errors')

            {!! Form::model($event, array('method' => 'PATCH', 'route' => array(config('admin.route').'.events.update', $event->id))) !!}

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('Admin::admin.edit-item', ['item' => mb_strtolower(trans('Admin::models.' . ucfirst($menuRoute->singular_name)))]) }}
                    </h3>
                </div>

                <div class="box-body row">
                    <div class="form-group col-md-12">
                        {!! Form::label('name', __('Event')) !!}*
                        {!! Form::text('name', old('name', $event->name), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('date', __('Date')) !!}*
                        {!! Form::text('date', old('date', $event->date), array('class'=>'form-control datetimepicker')) !!}
                        <p class="help-block">{{ __('Select event date and time.') }}</p>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('city_id', __('City')) !!}*
                        {!! Form::select('city_id', $cities, old('city_id', $event->hall->building->city->id), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('building_id', __('Building')) !!}*
                        {!! Form::select('building_id', $buildings, old('building_id', $event->hall->building->id), array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('hall_id', __('Hall')) !!}*
                        {!! Form::select('hall_id', $halls, old('hall_id', $event->hall_id), array('class'=>'form-control')) !!}
                    </div>
                    <div class="checkbox col-md-12">
                        <label>
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" {{ $event->is_active ? "checked" : "" }}> {{ __('Active') }}
                        </label>
                    </div>
                </div>

                <div class="box-footer">
                    @include('Admin::partials.save-buttons')

                    {!! Form::hidden('id', $event->id) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')
    @include('Admin::event.partials._scripts')
@endsection
