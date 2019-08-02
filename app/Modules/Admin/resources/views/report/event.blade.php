@extends('Admin::layouts.master')

@section('page-header')
  @include('Admin::partials.page-header')
@endsection

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endsection

@section('content')

  <div id="mainContent" class="row" style="display: none">
    <form
        action="{{ route(config('admin.route') . '.reports.data.events') }}"
        class="report_form"
        data-target="#reportBody"
        data-target-container="#reportBody"
    >
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <label for="event_period">{{ __('Event dates range') }}</label>
          </div>

          <div class="box-body">
            <div class="row">
              <div class="col-xs-12 col-md-6 col-lg-4 col-xl-3">
                <section
                    id="event_period"
                    class="date_range_picker period-search"
                    data-name="event_period"
                    data-class="form-search"
                    data-start="{{ $eventsPeriodStart }}"
                    data-end="{{ $eventsPeriodEnd }}"
                ></section>
              </div>
            </div>
          </div>
        </div>

        <div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <table class="events-report-table">
              <tbody>
              <tr>
                <td>
                  <input type="text" name="event_id" class="form-control form-search">
                </td>
                <td>
                  <select
                      data-allow-clear="false"
                      data-minimum-results-for-search="-1"
                      name="is_active"
                      data-placeholder="{{ __('<All>') }}"
                      class="form-control form-search">
                    <option value="_all_" selected="selected">{{ __('<All>') }}</option>
                    <option value="1">{{ __('Yes') }}</option>
                    <option value="0">{{ __('No') }}</option>
                  </select>
                </td>
                <td>
                  <input type="text" class="form-control form-search" disabled="disabled">
                </td>
                <td>
                  <input type="text" class="form-control form-search" disabled="disabled">
                </td>
                <td>
                  <input type="text" name="event_name" class="form-control form-search">
                </td>
                <td>
                  <input type="text" name="event_city" class="form-control form-search">
                </td>
                <td>
                  <input type="text" name="event_building" class="form-control form-search">
                </td>
                <td>
                  <input type="text" name="event_hall" class="form-control form-search">
                </td>
                <td></td>
              </tr>
              </tbody>

              <tbody class="heading">
              <tr>
                <td>{{ __('ID') }}</td>
                <td>{{ __('Is active?') }}</td>
                <td>{{ __('Date') }}</td>
                <td>{{ __('Time') }}</td>
                <td>{{ __('Event') }}</td>
                <td>{{ __('City') }}</td>
                <td>{{ __('Building name') }}</td>
                <td>{{ __('Hall') }}</td>
                <td>{{ __('Actions') }}</td>
              </tr>
              </tbody>

              <tbody id="reportBody" class="body" style="display: none"></tbody>

            </table>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('after_scripts')
  @include('Admin::partials.date-range-picker-scripts')
  @include('Admin::partials.select-scripts')
  @include('Admin::partials.report-form-scripts')

  <script>
    jQuery(document).ready(function() {
      $('#mainContent').show();

      var $form = $('.report_form');
      $form.submit();

      $('.form-search').change(function() {
        setTimeout(function () {
          $form.submit();
        }, 200);
      });
    });
  </script>
@endsection