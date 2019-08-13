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

            {!! Form::open(['route' => config('admin.route').'.events.store', 'files' => true]) !!}
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('Admin::admin.add-new', ['item' => mb_strtolower(trans('Admin::models.' . ucfirst($menuRoute->singular_name)))]) }}
                    </h3>
                </div>

                <div class="box-body row">
                    <div class="col-md-8">
                        <div class="form-group">
                            {!! Form::label('name', __('Event')) !!}*
                            {!! Form::text('name', old('name'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('date', __('Date')) !!}*
                            {!! Form::text('date', old('date'), array('class'=>'form-control datetimepicker')) !!}
                            <p class="help-block">{{ __('Select event date and time.') }}</p>
                        </div>
                        <div class="form-group">
                            {!! Form::label('city_id', __('City')) !!}*
                            {!! Form::select('city_id', $cities, old('city_id'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('building_id', __('Building')) !!}*
                            {!! Form::select('building_id', $buildings, old('building_id'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('hall_id', __('Hall')) !!}*
                            {!! Form::select('hall_id', $halls, old('hall_id'), array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <div class="free-pass-container">
                                <label>{{ __('Free pass') }}</label>
                                <div id="freePass" class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-file btn-file-upload">
                                        {{ __('Select logo') }} <input type="file" name="free_pass_logo" accept="{{ FileHelper::mimesImage() }}">
                                    </span>
                                </span>
                                    <input type="text" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active"> {{ __('Active') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="thumbnail-container">
                            <label>{{ __('Thumbnail') }}</label>
                            <div class="image-preview"></div>
                            <label class="btn btn-file-upload w100">
                                {{ __('Select thumbnail') }} <input type="file" class="upload-file" name="event_image" accept="{{ FileHelper::mimesImage() }}">
                            </label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="MAX_FILE_SIZE" value="{{ FileHelper::maxUploadSize() }}">

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
    @include('Admin::event.partials._scripts')
@endsection
