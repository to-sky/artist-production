@extends('Admin::layouts.master')

@section('page-header')
  @include('Admin::partials.page-header')
@endsection

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endsection

@section('content')

  <div id="mainContent" class="row" style="display: none">
    <div class="col-md-6 col-lg-4">
      <form
          action="{{ route(config('admin.route') . '.reports.data.by_partner') }}"
          class="report_form"
          data-target="#reportData"
          data-target-container="#reportContainer"
      >
        <div class="box">
          <div class="box-header with-border">
            <label for="report_period">{{ __('Report period') }}</label>
          </div>

          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <section
                    id="report_period"
                    class="date_range_picker period-search"
                    data-name="sale_period"
                    data-start="{{ $salesStart }}"
                    data-end="{{ $salesEnd }}"
                ></section>
              </div>
            </div>
          </div>
        </div>

        <div class="box">
          <div class="box-header with-border">
            <label for="event_name">{{ __('Events') }}</label>
          </div>

          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <select name="event_name[]" id="event_name" class="form-control event_id_trigger" multiple>
                  @foreach($eventNames as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-xs-12">
                <label for="event_range">{{ __('Event dates range') }}</label>

                <section
                    id="event_range"
                    class="date_range_picker period-search event_id_trigger_wrap"
                    data-name="event_range"
                ></section>
              </div>

              <div class="col-xs-12">
                <label for="event_id">{{ __('Select event') }}</label>

                <select name="event_ids[]" id="event_id" class="form-control" multiple></select>
              </div>
            </div>
          </div>
        </div>

        <div class="box">
          <div class="box-header with-border">
            <label for="with">{{ __('Partner') }}</label>
          </div>

          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <select name="with[]" id="with" class="form-control" multiple>
                  @foreach($partnerOptions as $groupName => $groupOptions)
                    <optgroup label="{{ $groupName }}">
                      @foreach($groupOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                      @endforeach
                    </optgroup>
                  @endforeach
                </select>
              </div>

              <div class="col-xs-12">
                <label for="without">{{ __('Exclude') }}</label>

                <select name="without[]" id="without" class="form-control" multiple>
                  @foreach($excludeOptions as $groupName => $groupOptions)
                    <optgroup label="{{ $groupName }}">
                      @foreach($groupOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                      @endforeach
                    </optgroup>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="box">
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <label for="order_statuses">{{ __('Order status') }}</label>

                <select name="order_statuses[]" id="order_statuses" class="form-control" multiple>
                  @foreach($orderStatusOptions as $value => $label)
                    <option value="{{ $value }}" @if(in_array($value, $defaultStatuses)) selected @endif>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="box">
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <label>{{ __('Group by') }}</label>

                <div>
                  <input type="radio" id="group_event" name="group_by" value="event_name" checked>
                  <label for="group_event">{{ __('events') }}</label>

                  <input type="radio" id="group_manager" name="group_by" value="manager_name">
                  <label for="group_manager">{{ __('partners') }}</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box">
          <div class="box-body">
            <button type="submit" class="btn btn-primary">{{ __('Create report') }}</button>
            <button
                type="button"
                class="btn btn-secondary r_export_excel"
                data-href="{{ route(config('admin.route') . '.reports.export.by_partner') }}"
            >{{ __('Export to Excel') }}</button>
          </div>
        </div>
      </form>
    </div>

    <div id="reportContainer" class="col-md-6 col-lg-8" style="display: none">
      <div class="box">
        <div class="box-body">
          <section id="reportData"></section>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('after_scripts')
  <script>
    jQuery(document).ready(function() {
      $('#mainContent').show();
    });
  </script>

  @include('Admin::partials.date-range-picker-scripts')
  @include('Admin::partials.select-scripts')
  @include('Admin::partials.report-form-scripts')
@endsection