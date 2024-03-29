@extends('Admin::layouts.master')

@section('page-header')
    @include('Admin::partials.page-header')
@endsection

@section('content')
    @include('Admin::partials.modal-delete-item-confirm')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <a href="{{ route(config('admin.route').'.events.index') }}">
                <i class="fa fa-angle-double-left"></i>
                {{ trans('Admin::admin.back-to-all-entries') }}
            </a>
            <br><br>

            <ul class="nav nav-tabs" role="tablist" id="eventTabs">
                <li role="presentation">
                    <a href="#event" aria-controls="event" role="tab" data-toggle="tab">{{ __('Event') }}</a>
                </li>
                <li role="presentation">
                    <a href="#prices" aria-controls="prices" role="tab" data-toggle="tab">{{ __('Prices') }}</a>
                </li>
            </ul>

            {!! Form::model($event, array('method' => 'PATCH', 'route' => array(config('admin.route').'.events.update', $event->id), 'files' => true)) !!}
            {!! Form::hidden('id', $event->id) !!}

            <div class="tab-content">
                @include('Admin::partials.errors')

                    <div role="tabpanel" class="tab-pane" id="event">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    {{ trans('Admin::admin.edit-item', ['item' => mb_strtolower(trans('Admin::models.' . ucfirst($menuRoute->singular_name)))]) }}
                                </h3>
                            </div>

                            <div class="box-body row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        {!! Form::label('name', __('Event')) !!}*
                                        {!! Form::text('name', old('name', $event->name), array('class'=>'form-control')) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('date', __('Date')) !!}*
                                        {!! Form::text('date', old('date', $event->date), array('class'=>'form-control datetimepicker')) !!}
                                        <p class="help-block">{{ __('Select event date and time.') }}</p>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('city_id', __('City')) !!}*
                                        {!! Form::select('city_id', $cities, old('city_id', $event->hall->building->city->id), array('class'=>'form-control')) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('building_id', __('Building')) !!}*
                                        {!! Form::select('building_id', $buildings, old('building_id', $event->hall->building->id), array('class'=>'form-control')) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('hall_blueprint_id', __('Hall')) !!}*
                                        {!! Form::select('hall_blueprint_id', $halls, old('hall_blueprint_id', $event->hall_blueprint_id), array('class'=>'form-control')) !!}
                                    </div>
                                    <div class="form-group">
                                        <div class="free-pass-container">
                                            <label>{{ __('Free pass') }}</label>
                                            <div class="row">
                                                <div id="freePass" class="col-md-12">
                                                    <label class="col-md-3 btn btn-file-upload br-none">
                                                        {{ __('Select logo') }} <input type="file" name="free_pass_logo" accept="{{ FileHelper::mimesImage() }}">
                                                    </label>

                                                    <label class="free-pass-input @if(empty($event->freePassLogo)) col-md-9 @else col-md-8 @endif">
                                                        <input type="text" class="form-control" readonly
                                                               value="{{ $event->freePassLogo->original_name ?? '' }}">
                                                    </label>

                                                    @isset($event->freePassLogo)
                                                        <button class="col-md-1 btn btn-file-upload bl-none"
                                                                type="button"
                                                                data-toggle="modal"
                                                                data-target="#deleteItem"
                                                                data-url="{{ route('events.deleteFreePassLogo', ['id' => $event->id]) }}"
                                                                data-reload="true">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </button>
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" name="is_active" {{ $event->is_active ? "checked" : "" }}> {{ __('Active') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="thumbnail-container">
                                        <label>{{ __('Thumbnail') }}</label>
                                        <div class="image-preview"
                                             style="background-image: url('{{ $event->image_url }}');">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="col-md-10 btn btn-file-upload @empty($event->eventImage) w100 @endempty">
                                                    {{ __('Select thumbnail') }} <input type="file" class="upload-file" name="event_image" {{ FileHelper::mimesImage() }}>
                                                </label>

                                                @isset($event->eventImage)
                                                    <button class="col-md-2 btn btn-file-upload bl-none"
                                                            type="button"
                                                            data-toggle="modal"
                                                            data-target="#deleteItem"
                                                            data-url="{{ route('events.deleteEventImage', ['id' => $event->id]) }}"
                                                            data-reload="true">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </button>
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="MAX_FILE_SIZE" value="{{ FileHelper::maxUploadSize() }}">
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="prices">
                        @include('Admin::event.partials._prices')
                    </div>

                @include('Admin::partials.save-buttons')
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after_scripts')
    @include('Admin::partials.form-scripts')
    @include('Admin::event.partials._scripts')

    <script>
        // Set active tab
        var url = window.location.href;
        var tabName = url.indexOf("#") + 1;
        var activeTab = tabName ? url.substring(tabName) : 'event';
        $("#" + activeTab).addClass("active");
        $('a[href="#'+ activeTab +'"]').tab('show');

        // Add price row
        var amountPrices = $('#pricesTable tbody tr:not(.hidden, .empty-row)').length;
        $('#addPrice').click(function () {
            amountPrices++;

            cloneRow('.prices-row', amountPrices).find('.colorpicker-block input').colorpicker();
        });

        // Add price groups row
        var amountPriceGroups = $('#priceGroupTable tbody tr:not(.hidden, .empty-row)').length;
        $('#addPriceGroup').click(function () {
            amountPriceGroups++;

            cloneRow('.price-groups-row', amountPriceGroups);
        });

        // Add colorpicker
        $('tr:not(.hidden) .colorpicker-block input').colorpicker();

        // Clone table row
        function cloneRow(el, position) {
            var currentRow = $(el);
            var cloneRow = currentRow.first().clone();

            currentRow.parent('tbody').find('tr.empty-row').remove();

            setInputName(cloneRow, position);

            cloneRow.removeClass('hidden');

            currentRow.last().after(cloneRow);

            return cloneRow
        }

        // Set input name
        function setInputName(row, position) {
            row.find('input').each(function(i, el) {
                $(el).prop('disabled', false);

                var inputName = $(el).attr('name');
                var inputNameArray = inputName.split('-');
                inputNameArray.splice(1, 0,'[' + position + ']');

                $(el).attr('name', inputNameArray.join(''));
            });
        }
    </script>
@endsection
